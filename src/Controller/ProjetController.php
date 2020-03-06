<?php
namespace App\Controller;

require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'VerificationChamps.php');
require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'ModificationsProjet.php');
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ProjetController extends AppController
{
  /**
  *  Affiche la liste des projets dont l'utilisateur est membre.
  * TODO: Il faut afficher les listes dont l'utilisateur est membre et non celles pour lesquelles il est propriétaire.
  * @author : POP Diana
  */
    public function index()
    {
        $this->loadComponent('Paginator');
        $session = $this->request->getSession();
        // $projets = $this->Paginator->paginate($this->Projet->find()->contain(['Membre'])->where(['idUtilisateur' => $session->read('Auth.User.idUtilisateur')]));
        $projets = $this->Paginator->paginate($this->Projet->find()->distinct()->contain('Utilisateur')
        ->leftJoinWith('Membre')
        ->where(
          ['etat !=' => 'Archive', 'OR' => [
              'Membre.idUtilisateur' => $session->read('Auth.User.idUtilisateur'),
              'Projet.idProprietaire' => $session->read('Auth.User.idUtilisateur')
         ]]));

        $this->set(compact('projets'));
    }

    /**
    * Créer un projet dont l'utilisateur connecté sera le propriétaire.
    * Une ligne dans Membre est donc créée.
    * @author : POP Diana, TABARY Mathieu, PALMIERI Adrien
    * Le fichier lié à cet affichage est 'Projet/add.ctp'
    * La page chargé si une demande de création de projet est faite est la liste des projets de l'utilisateur.
    */
    public function add(){
        // Récuperation de données pour l'affichage de la page de création
        $today = Time::now();
        $this->set(compact('today'));

        // Vérification des données envoyées pour la création
        if ($this->request->is('post')){
            // Récupération de l'identité du créateur de projet
            $session = $this->request->getSession();
            $idUtilisateur = $session->read('Auth.User.idUtilisateur');

            $receivedData = $this->request->getData();
            $receivedData['titre'] = nettoyerTexte($receivedData['titre']);
            $receivedData['description'] = nettoyerTexte($receivedData['description']);
            $existeErreur = false;

            // Vérification des saisies utilisateurs
            // Vérification du titre
            if(!verificationTitre($receivedData['titre'])){
                // Si le titre n'est pas correct
                $this->Flash->error(__("Titre incorrect (doit avoir entre 1 et 128 caractères)"));
                $existeErreur = true;
            }

            // Vérification de la description
            if(!verificationDescription($receivedData['description'])){
                // Si la description n'est pas correcte
                $this->Flash->error(__("La description est trop longue (max 512 caractères)."));
                $existeErreur = true;
            }

            $receivedData['dateFin'] = nettoyageDate($receivedData['dateFin']);
            // Si la date était incorrecte on affiche un message pour l'utilisateur
            if($receivedData['dateFin'] == null){
                $this->Flash->warning(__("Votre date de fin étant incorrecte (au moins un champ vide), elle n'a pas été enregistrée"));
            }

            // Vérification des dates
            if(!verificationDates($receivedData['dateDebut'], $receivedData['dateFin'])){
                // Si les dates ne sont pas cohérentes
                $this->Flash->error(__("La fin du projet ne peut pas se faire avant le début de ce projet"));
                $existeErreur = true;
            }

            // Vérification de la date de fin
            if(!verificationDateFin($receivedData['dateFin'])){
                // Si la date de fin est antérieur à la date du jour
                $this->Flash->error(__("La fin du projet ne peut pas se faire avant la date du jour"));
                $existeErreur = true;
            }

            // On vérifie s'il existe un projet créer par l'utilisateur courrant ayant le nom du projet en cours de création
            $listeProjetsUtilisateur = $this->Projet->find('all', ['conditions'=>['idProprietaire'=>$idUtilisateur]]);
            foreach($listeProjetsUtilisateur as $proj) {
                if($proj->titre == $receivedData['titre']) {
                    $this->Flash->error(__("Impossible d'ajouter un projet avec un nom identique"));
                    $existeErreur = true;
                }
            }

            if($existeErreur){
                return $this->redirect(['action'=> 'index']);
            }

            // Tout les tests se sont bien déroulés, on commence à créer le projet
            $projet = $this->Projet->newEntity($receivedData);
            $projet->idProprietaire = $idUtilisateur;

            // On enregistre le projet dans la base de données
            if ($this->Projet->save($projet)) {
                ajouterMembre($projet->idProjet, $idUtilisateur);
                /*
                // TODO : utiliser la méthode d'ajout de membre à un projet
                // On ajoute le créateur du projet en tant que membre de ce projet
                $membres = TableRegistry::getTableLocator()->get('Membre');
                $membre = $membres->newEntity();
                $membre->set('idUtilisateur', $idUtilisateur);
                $membre->set('idProjet', $projet->idProjet);
                if ($membres->save($membre)) {
                    $this->Flash->success(__('Votre projet a été sauvegardé.'));
                    return $this->redirect(['action'=> 'index']);
                }else {
                    // Si il y a eu une erreur lors de l'ajout du membre dans la database
                    $this->Flash->error(__("Impossible d'ajouter votre projet."));
                    return $this->redirect(['action'=> 'index']);
                }
                */
            } else{
                // Si il y a eu une erreur lors de l'ajout du projet dans la database
                $this->Flash->error(__("Impossible d'ajouter votre projet."));
            }
            return $this->redirect(['action'=> 'index', $projet->idProjet]);
        }
    }

    /**
    * liste les projets archivés
    * @author WATELOT Paul-Emile
    */
    public function archives(){
      $projets = TableRegistry::getTableLocator()->get('Projet');

      $session = $this->request->getSession();
      $archives = $projets->find()->distinct()->contain('Utilisateur')
      ->leftJoinWith('Membre')
      ->where(
        ['etat' => 'Archive', 'OR' => [
            'Membre.idUtilisateur' => $session->read('Auth.User.idUtilisateur'),
            'Projet.idProprietaire' => $session->read('Auth.User.idUtilisateur')
       ]]);

      $this->set(compact('archives'));

    }

    /**
    * Supprime un projet si propriétaire et enleve un membre du groupe si il quitte
    * @author WATELOT Paul-Emile
    */
    public function delete($idProjet){
        $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
          ->get('Projet')->find()
          ->where(['idProjet' => $idProjet])
          ->first();

        //permet de savoir si un utilisateur est propriétaire
        $session = $this->request->getSession();
        if ($session->check('Auth.User.idUtilisateur')) {
          $idUser = $session->read('Auth.User.idUtilisateur');
          $membres = TableRegistry::getTableLocator()->get('Membre');
          if($projetTab->idProprietaire == $idUser){

            //degage tout les membres du projet
            $query = $membres->query();
            $query->delete()->where(['idProjet' => $idProjet])->execute();

            //supprime les taches du projet
            $taches = TableRegistry::getTableLocator()->get('Tache');
            $query = $taches->query();
            $query->delete()->where(['idProjet' => $idProjet])->execute();

            //supprime le projet
            $projets = TableRegistry::getTableLocator()->get('Projet');
            $query = $projets->query();
            $query->delete()->where(['idProjet' => $idProjet])->execute();

          }else{
            //sinon si c'est un invité on le degage dans la table membre
            $tachesSousResponsabilite = TableRegistry::getTableLocator()
                ->get('Tache')->find()
                ->where(['AND' => ['idProjet' => $idProjet, 'idResponsable' => $idUser]])
                ->all();
            foreach($tachesSousResponsabilite as $tache):
                (new TacheController)->notSoResponsible($idProjet, $tache->idTache);
            endforeach;
            $query = $membres->query();
            $query->delete()->where(['idProjet' => $idProjet, 'idUtilisateur' => $idUser])->execute();
          }
        }

        return $this->redirect(['action'=> 'index']);
    }

    /**
     * Permet d'archiver un projet uniquement si il est expiré et si l'utilisateur en est le propriétaire
     * @param int $idProjet ID du projet a archiver
     * @author Pedro Sousa Ribeiro
     * 
     * Redirection: Si l'utilisateur n'est pas connecté OU s'il n'est pas le propriétaire du projet OU si le projet n'est pas expiré, 
     *              l'utilisateur est redirigé vers la dernière page qu'il a visité (la page d'où il vient).
     *              Sinon si tout va bien l'utilisateur est dirigé vers la liste des projets archivés
     */
    public function archive($idProjet) {
      $projet = $this->Projet->get($idProjet);
      $now = Time::now();

      $session = $this->request->getSession();
      if ($session->check('Auth.User.idUtilisateur')) {
        $user = $session->read('Auth.User.idUtilisateur');
        if ($projet->dateFin < $now) {
          if ($user === $projet->idProprietaire) {
            $projet->etat = "Archive";
            $projet->dateArchivage = Time::now();
            $this->Projet->save($projet);

            // Projet archivé
            $this->Flash->success(__("Projet achivé avec succès"));
            $this->redirect(['action' => 'archives']);
          } else { // Pas le propriétaire
            $this->Flash->error(__("Seul le propriétaire est en mesure d'archiver le projet."));
            $this->redirect($this->referer());
          }
        } else { // Projet non expiré
          $this->Flash->error(__("Le projet doit être expiré pour pouvoir l'archiver."));
          $this->redirect($this->referer());
        }
      } else {
        $this->Flash->error(__("Vous devez être connecté pour archiver un projet."));
        $this->redirect($this->referer());
      }
    }

    /**
    * @author Théo Roton
    * @param id : id du projet pour lequel on affiche l'écran de modification
    * Cette fonction affiche l'écran de modification pour le projet identifié
    * par l'id passé en paramètre. On récupère les informations du projet afin
    * de remplir les champs du formulaire avec.
    * Le fichier lié à l'affichage de cette page est 'Projet/edit.ctp'.
    */
    public function edit($id){
      //On récupère le projet
      $projet = TableRegistry::getTableLocator()->get('projet');
      $projet = $projet->find()
      ->where(['idProjet' => $id])
      ->first();

      // On récupère la date du jour pour avoir une année minimum dans les champs dates du formulaire
      $today = Time::now();

      $this->set(compact('projet','id','today'));
    }

    /**
    * @author Théo Roton
    * Cette fonction permet de vérifier les informations modifiés pour
    * le projet. On effectue plusieurs vérifications : s'il y a des erreurs,
    * on renvoie l'utilisateur sur la page de modification en indiquant
    * les champs qui présentent une erreur, sinon on renvoie l'utilisateur
    * sur la page du projet qu'il a modifié avec les informations mises
    * à jour (et avec un message de succès).
    */
    public function modifierInfos(){
      $receivedData = $this->request->getData();
      echo "<pre>" , var_dump($receivedData) , "</pre>";

      // On récupère le projet pour avoir les anciennes informations
      $projets = TableRegistry::getTableLocator()->get('projet');
      $projet = $projets->find()
      ->where(['idProjet' => $receivedData['id']])
      ->first();

      $erreur = false;

      // Si on a modifié le titre
      if ($projet->titre != $receivedData['titre']){
          // On vérifie si le nouveau titre est bien formé
          if (verificationTitre($receivedData['titre'])){

            // On vérifie si le nouveau titre n'est pas pris par un autre projet de l'utilisateur
            $session = $this->request->getSession();
            $existe_deja = $projets->find()
            ->where(['idProprietaire' => $session->read('Auth.User.idUtilisateur')])
            ->where(['titre' => $receivedData['titre']])
            ->count();

            if ($existe_deja == 0){
              // Si le nouveau titre respecte les contraintes, on modifie le projet
              // $projet->titre = filter_var($receivedData['titre'],FILTER_SANITIZE_STRING);
                $projet->titre = nettoyerTexte($receivedData['titre']);
            } else {
              // Si le titre est déjà pris, on affiche une erreur
              $this->Flash->error(__("Vous avez déjà un projet avec ce titre."));
              $erreur = true;
            }
          } else {
            // Si le titre ne respecte pas la contrainte de taille, on affiche une erreur
            $this->Flash->error(__("La taille du titre est incorrecte (128 caractères max)."));
            $erreur = true;
          }
      }

      // On récupère la date d'aujourd'hui
      $today = date('Y-m-d');
      $today = explode('-',$today);

      /**
      * Si la nouvelle date de début du projet n'est pas avant la date d'ajourd'hui,
      * ou si le projet est archivé.
      */
      if (verificationDates($today, $receivedData['dateDeb']) || $projet->etat = 'Archive'){

        $dF = $receivedData['dateFin'];
        // Si on a une date de fin
        if (strlen($dF['year']) > 0 && strlen($dF['month']) > 0 && strlen($dF['day']) > 0){

          // Si la date de début est avant celle de fin
          if (verificationDates($receivedData['dateDeb'],$receivedData['dateFin'])){

            // On modifie le projet avec les nouvelles dates
            $projet->dateDebut = date('Y-m-d',strtotime(implode($receivedData['dateDeb'])));
            $projet->dateFin = date('Y-m-d',strtotime(implode($receivedData['dateFin'])));

            // Si le projet était archivé, et que la nouvelle date de fin est après aujourd'hui, on met le projet en cours
            if ($projet->etat == 'Archive' && verificationDates($today, $receivedData['dateFin'])) {
              $projet->etat = 'En cours';
            }

          } else {
            // Si la nouvelle date de début est après celle de fin, on affiche une erreur
            $this->Flash->error(__("La date de début du projet ne peut pas être après celle de fin."));
            $erreur = true;
          }
          // Si on a pas de date de fin
        } else if (strlen($dF['year']) == 0 && strlen($dF['month']) == 0 && strlen($dF['day']) == 0) {

          // On modifie le projet avec la nouvelle date de début et aucune date pour la date de fin
          $projet->dateDebut = date('Y-m-d',strtotime(implode($receivedData['dateDeb'])));
          $projet->dateFin = null;

          // Si le projet était archivé, alors on le met en cours
          if ($projet->etat == 'Archive') {
            $projet->etat = 'En cours';
          }

        } else {
          // Si la date de fin est mal formée, on affiche une erreur
          $this->Flash->error(__("La date de fin du projet est mal formée."));
          $erreur = true;
        }
      } else {
        // Si la date du début du projet est avant aujourd'hui, on affiche une erreur
        $this->Flash->error(__("La date de début du projet ne peut pas être avant aujourd'hui."));
        $erreur = true;
      }

      // Si on a modifié la description
      if ($projet->description != $receivedData['descr']){

        // On vérifie si la nouvelle description est bien formée
        if (verificationDescription($receivedData['descr'])){
          // Si la nouvelle description respecte les contraintes, alors on modifie le projet
          $projet->description = nettoyerTexte($receivedData['descr']);
        } else {
          // Si la description ne respecte pas la contrainte de taille, on affiche une erreur
          $this->Flash->error(__("La taille de la description est incorrecte (500 caractères)."));
          $erreur = true;
        }
      }

      // Si on n'a pas eu d'erreur alors
      if (!$erreur){
        // On sauvegarde les nouvelles informations du projet
        $projets->save($projet);
        // On indique que la modification a réussie
        $this->Flash->success(__('Votre projet a été modifé.'));

        // On redirige l'utilisateur sur le projet avec les informations mises à jour
        return $this->redirect(
            array('controller' => 'Tache', 'action' => 'index', $receivedData['id'])
        );
      } else {
        // Si il y a eu une erreur, on renvoie l'utilisateur sur la page de modification
        $this->redirect($this->referer());
      }
    }

    /**
     * Change le propriétaire d'un projet
     * @param   $idMembre id du membre qui devient propriétaire du projet
     * @param   $idProjet id du projet
     * @author  Clément Colné
     */
    function changerProprietaire($idMembre, $idProjet) {
      $projets = TableRegistry::get('Projet');
      // on récupère l'ID du propriétaire
      $projet = $projets->find()->where(['idProjet'=>$idProjet])->first();
      // mise à jour du nouveau propriétaire dans la DB
      $query = $projets->query();
      $query->update()
        ->set(['idProprietaire' => $idMembre])
        ->where(['idProjet' => $idProjet])->execute();
      // redirection vers la page d'accueil des projets
      return $this->redirect(['controller'=>'Tache', 'action'=> 'index', $idProjet]);
    }

}
?>
