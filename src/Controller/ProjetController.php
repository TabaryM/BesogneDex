<?php
namespace App\Controller;

require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'VerificationChamps.php');
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ProjetController extends AppController
{
  /**
  *  Affiche la liste des projets dont l'utilisateur est membre.
  *
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
    * Crée un projet dont l'utilisateur connecté sera le propriétaire.
    * Une ligne dans Membre est donc créée.
    *
    * @authors : POP Diana, TABARY Mathieu, PALMIERI Adrien
    */
    public function add(){
      if ($this->request->is('post')){
        $receivedData = $this->request->getData();

          // Vérification des saisies utilisateurs
          if(verification_titre($receivedData['titre'])){
              if(verification_description($receivedData['description'])){
                  if(verification_dates($receivedData['dateDebut'], $receivedData['dateFin'])){
                      $dateDuJour = getdate();
                      if($receivedData['dateDebut'] == $receivedData['dateFin']){

                      }
                      $projet = $this->Projet->newEntity($receivedData);
                      $session = $this->request->getSession();
                      $idUser = $session->read('Auth.User.idUtilisateur');
                      $projet->idProprietaire = $idUser;
                      foreach($this->Projet->find('all', ['conditions'=>['idProprietaire'=>$idUser]]) as $proj) {
                          if($proj->titre == $receivedData['titre']) {
                              $this->Flash->error(__("Impossible d'ajouter un projet avec un nom identique"));
                              return $this->redirect(['action'=> 'index']);
                          }
                      }
                      if ($this->Projet->save($projet)) {
                          $membres = TableRegistry::getTableLocator()->get('Membre');
                          $membre = $membres->newEntity();
                          $membre->set('idUtilisateur', $idUser);
                          $membre->set('idProjet', $projet->idProjet);

                          if ($membres->save($membre)) {
                            $this->Flash->success(__('Votre projet a été sauvegardé.'));
                            return $this->redirect(['action'=> 'index']);
                          }else {
                            // Si il y a eu une erreur lors de l'ajout du membre dans la database
                            $this->Flash->error(__("Impossible d'ajouter votre projet."));
                            return $this->redirect(['action'=> 'index']);
                          }
                      }
                      // Si il y a eu une erreur lors de l'ajout du projet dans la database
                      $this->Flash->error(__("Impossible d'ajouter votre projet."));

                  } else {
                      // Si les dates ne sont pas cohérentes
                      $this->Flash->error(__("La fin du projet ne peut pas se faire avant le début de ce projet"));
                  }

              } else {
                  // Si la description n'est pas correcte
                  $this->Flash->error(__("La description est trop longue"));
              }

          } else {
              // Si le titre n'est pas correcte
            $this->Flash->error(__("Titre incorrecte (doit avoir entre 1 et 128 caractères)"));
          }
      }
    }

    /**
    * liste les projets archivés
    *
    * Auteurs : WATELOT Paul-Emile
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
    *
    * Auteurs : WATELOT Paul-Emile
    */
    public function delete($idProjet){
      if ($this->request->is('post')){

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

          }
          //sinon si c'est un invité on le degage dans la table membre
          else{
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
    }

    /**
     * Permet d'archiver un projet uniquement si il est expiré et si l'utilisateur en est le propriétaire
     * @param int $idProjet ID du projet a archiver
     * @author Pedro Sousa Ribeiro
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
      }
    }


    /**
    * @author Théo Roton
    */
    public function edit($id){
      $projet = TableRegistry::getTableLocator()->get('projet');
      $projet = $projet->find()
      ->where(['idProjet' => $id])
      ->first();

      $today = Time::now();

      $this->set(compact('projet','id','today'));
    }

    /**
    * @author Théo Roton
    * @TODO ajouter les erreurs
    */
    public function modifierInfos(){
      $receivedData = $this->request->getData();
      echo "<pre>" , var_dump($receivedData) , "</pre>";

      $projets = TableRegistry::getTableLocator()->get('projet');
      $projet = $projets->find()
      ->where(['idProjet' => $receivedData['id']])
      ->first();

      $erreur = false;

      if ($projet->titre != $receivedData['titre']){
          if (verification_titre($receivedData['titre'])){
            $session = $this->request->getSession();
            $existe_deja = $projets->find()
            ->where(['idProprietaire' => $session->read('Auth.User.idUtilisateur')])
            ->where(['titre' => $receivedData['titre']])
            ->count();

            if ($existe_deja == 0){
              $projet->titre = filter_var($receivedData['titre'],FILTER_SANITIZE_STRING);
            } else {
              //titre déja pris
                echo "titre déjà pris\n";
              $erreur = true;
            }
          } else {
            //titre incorrect
              echo "titre incorrect\n";
            $erreur = true;
          }
      }

      $today = date('Y-m-d');
      $today = explode('-',$today);
      if (verification_dates($today, $receivedData['dateDeb']) || $projet->etat = 'Archive'){

        $dF = $receivedData['dateFin'];
        if (strlen($dF['year']) > 0 && strlen($dF['month']) > 0 && strlen($dF['day']) > 0){

          if (verification_dates($receivedData['dateDeb'],$receivedData['dateFin'])){

            $projet->dateDebut = date('Y-m-d',strtotime(implode($receivedData['dateDeb'])));
            $projet->dateFin = date('Y-m-d',strtotime(implode($receivedData['dateFin'])));

            if ($projet->etat == 'Archive' && verification_dates($today, $receivedData['dateFin'])) {
              $projet->etat = 'En cours';
            }

          } else {

            //date de début après fin
              echo "date de début après fin\n";
            $erreur = true;
          }
        } else if (strlen($dF['year']) == 0 && strlen($dF['month']) == 0 && strlen($dF['day']) == 0) {
          $projet->dateDebut = date('Y-m-d',strtotime(implode($receivedData['dateDeb'])));
          $projet->dateFin = null;
          if ($projet->etat == 'Archive') {
            $projet->etat = 'En cours';
          }

        } else {
          //date de fin mal formée
            echo "date de fin mal formée\n";
          $erreur = true;
        }
      } else {
        //date de debut avant aujourd'hui
          echo "date de debut avant hoy\n";
        $erreur = true;
      }

      if ($projet->description != $receivedData['descr']){
        if (verification_description($receivedData['descr'])){
          $projet->description = filter_var($receivedData['descr'],FILTER_SANITIZE_STRING);
        } else {
          //description incorrect
          echo "description incorrecte\n";
          $erreur = true;
        }
      }

      if (!$erreur){
        $projets->save($projet);
        return $this->redirect(
            array('controller' => 'Tache', 'action' => 'index', $receivedData['id'])
        );
      } else {
        $this->redirect($this->referer());
      }
    }
}
?>
