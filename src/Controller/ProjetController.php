<?php
namespace App\Controller;

require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'VerificationChamps.php');
require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'ModificationsProjet.php');
require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'Notifications.php');


use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class ProjetController extends AppController
{

  /**
  * Vérifie si l'utilisateur est propriétaire du projet donné.
  *
  * @param idProjet : id du projet
  * @return user id du Propriétaire si l'utilisateur est propriétaire du projet donné (mais ce n'est pas utile)
  *
  * Redirection (si l'utilisateur n'est pas connecté ou n'est pas propriétaire) : index de Accueil
  *
  * @author Diana POP
  **/
  private function autorisationProprietaire($idProjet){

    $projetTab = $this->Projet->find()->where(['idProjet' => $idProjet])->first();
    $session = $this->request->getSession();

    // Si l'utilisateur est connecté (le redirect pris en compte sera celui du Auth s'il n'est pas connecté)
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');

      // Si l'utilisateur est propriétaire
      if($projetTab->idProprietaire == $user){
        return $user;
      }
    }

    // Si l'utilisateur n'est pas le propriétaire ou si ce projet n'existe pas
    $this->Flash->error(__('Vous n\'êtes pas le/a propriétaire de ce projet ou ce projet n\'existe pas.'));
    $this->redirect(['controller'=>'Accueil', 'action'=>'index']);
  }

  /**
  * Vérifie si l'utilisateur est propriétaire ou membre du projet donné.
  *
  * @param idProjet : id du projet
  * @return Vrai si l'utilisateur est propriétaire ou membre du projet donné
  *
  * Redirection (si l'utilisateur n'est pas connecté ou n'est pas membre) : index de Accueil
  *
  * @author Diana POP
  **/
  private function autorisationMembre($idProjet){
    $projetTab = $this->Projet->find()->where(['idProjet' => $idProjet])->first();

    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      // L'utilisateur est-il propriétaire ?
      if($projetTab->idProprietaire == $user){
        return true;

        // S'il n'est pas propriétaire, est-il membre ?
      }else{
        $membres = TableRegistry::get('Membre');
        $query = $membres->find()->select(['idUtilisateur'])->where(['idUtilisateur' => $user, 'idProjet' => $idProjet])->count();

        // Renvoie vrai si l'utilisateur est membre.
        if ($query > 0){
          return true;
        }
      }
    }
    $this->Flash->error(__('Ce projet n\'existe pas ou vous n\'y avez pas accès.'));
    return $this->redirect(['controller'=>'Accueil', 'action'=>'index']);
  }


    /**
    * Supprime toutes les notifications associées à un projet et à ses tâches.
    *
    * @param idProjet : id du projet
    * @return /
    *
    * Redirection : /
    *
    * @author POP Diana
    */
    private function supprimerToutesNotifications($idProjet){
      // On récupère toutes les tables nécessaires .
      $taches = TableRegistry::getTableLocator()->get('Tache');
      $vuesNotificationsTaches = TableRegistry::getTableLocator()->get('VueNotificationTache');
      $vuesNotificationsProjets = TableRegistry::getTableLocator()->get('VueNotificationProjet');
      $notificationsProjets = TableRegistry::getTableLocator()->get('NotificationProjet');
      $notificationsTaches = TableRegistry::getTableLocator()->get('NotificationTache');

      /* NOTIFICATIONS TACHES */
      // On va commencer par supprimer toutes les notifications tâches.
      // On cherche toutes les tâches du projet pour avoir leur ids.
      $toutesTaches = $taches->find()->where(['idProjet' => $idProjet]);

      // On va aller voir chacune de ces tâches.
      foreach ($toutesTaches as $tache){
        $idTache = $tache->idTache;

        // Pour chacune, on trouve les notifications associées.
        $toutesNotifsTache = $notificationsTaches->find()->where(['idTache' => $idTache]);

        // On va aller voir chacune de ces notifications tâche.
        foreach ($toutesNotifsTache as $notifTache){
          // Pour chacune, on va supprimer les vues notifs tâche associées.
          $idNotifTache = $notifTache->idNotificationTache;
          $query = $vuesNotificationsTaches->query()->delete()->where(['idNotifTache' => $idNotifTache])->execute();

          // Maintenant que toutes les vues notifs tâches associées ont été supprimées, on peut effacer la notif tâche.
          $query = $notificationsTaches->query()->delete()->where(['idNotificationTache' => $idNotifTache])->execute();
        } // fin foreach $toutesNotifsTache
      } // fin foreach $toutesTaches

      /* NOTIFICATIONS PROJET */
      $toutesNotifsProjet = $notificationsProjets->find()->where(['idProjet'=> $idProjet]);

      foreach ($toutesNotifsProjet as $notifProjet){
        // Pour chacune, on va supprimer les vues notifs projet associées.
        $idNotifProjet = $notifProjet->idNotificationProjet;
        $query = $vuesNotificationsProjets->query()->delete()->where(['idNotifProjet' => $idNotifProjet])->execute();

        // Maintenant que toutes les vues notifs projet associées ont été supprimées, on peut effacer la notif projet.
        $query = $notificationsProjets->query()->delete()->where(['idNotificationProjet' => $idNotifProjet])->execute();
      }// fin foreach $toutesNotifsProjet


    }// fin fonction


  /**
  *  Affiche la liste des projets dont l'utilisateur est membre.
  *
  * @author : POP Diana
  */
  public function index()
  {
    $this->loadComponent('Paginator');
    $session = $this->request->getSession();
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
            $this->Flash->error(__("Le titre est incorrect (min 1 caractère, max 128 caractères)."));
            $existeErreur = true;
          }

          // Vérification de la description
          if(!verificationDescription($receivedData['description'])){
            // Si la description n'est pas correcte
            $this->Flash->error(__("La description est trop longue (max 512 caractères)."));
            $existeErreur = true;
          }

          // trois valeurs possibles pour le retour de nettoyage date : bien remplis; mal remplis; pas remplis
          switch (nettoyageDate($receivedData['dateFin'])){
            case "mal fait":
            $this->Flash->warning(__("Votre date de fin étant incorrecte (au moins un champ vide), elle n'a pas été enregistrée."));
            case "pas fait":
            $receivedData['dateFin'] = null;
            break;

          }
          // Vérification des dates
          if(!verificationDates($receivedData['dateDebut'], $receivedData['dateFin'])){
            // Si les dates ne sont pas cohérentes
            $this->Flash->error(__("La fin du projet ne peut pas être avant le début de ce projet."));
            $existeErreur = true;
          }

          // Vérification de la date de fin
          if(!verificationDateFin($receivedData['dateFin'])){
            // Si la date de fin est antérieur à la date du jour
            $this->Flash->error(__("La fin du projet ne peut pas se faire avant la date du jour."));
            $existeErreur = true;
          }

          // On vérifie s'il existe un projet créer par l'utilisateur courrant ayant le nom du projet en cours de création
          $listeProjetsUtilisateur = $this->Projet->find('all', ['conditions'=>['idProprietaire'=>$idUtilisateur]]);
          foreach($listeProjetsUtilisateur as $proj) {
            if($proj->titre == $receivedData['titre']) {
              $this->Flash->error(__("Impossible d'ajouter un projet avec un nom identique."));
              $existeErreur = true;
            }
          }

          // Tout les tests se sont bien déroulés, on commence à créer le projet
          if(!$existeErreur){
            $projet = $this->Projet->newEntity($receivedData);
            $projet->idProprietaire = $idUtilisateur;

            // On enregistre le projet dans la base de données
            if ($this->Projet->save($projet)) {
              ajouterMembre($projet->idProjet, $idUtilisateur);

              // Si il y a eu une erreur lors de l'ajout du projet dans la database
            }else{
              $this->Flash->error(__("Impossible d'ajouter votre projet."));
            }

            // Tout est ok.
            $this->redirect(['action'=> 'index', $projet->idProjet]);

            // S'il y a eu des erreurs dans la vérification
          }else{
            $this->redirect(['action'=> 'add']);
          }
        }// fin if post
      }// fin fonction

      /**
      * liste les projets archivés dont fait parti un membre
      *
      * @author WATELOT Paul-Emile
      */
      public function archives(){
        //recupere la table projet
        $projets = TableRegistry::getTableLocator()->get('Projet');

        $session = $this->request->getSession();
        //recupere les projets dont fait parti l'utilisateur et dont l'état est "Archive"
        $archives = $projets->find()->distinct()->contain('Utilisateur')
        ->leftJoinWith('Membre')
        ->where(
          ['etat' => 'Archive', 'OR' => [
            'Membre.idUtilisateur' => $session->read('Auth.User.idUtilisateur'),
            'Projet.idProprietaire' => $session->read('Auth.User.idUtilisateur')
            ]]);
            //partage la variables archives a Archives.ctp
            $this->set(compact('archives'));
          }

          /**
          * Supprime un projet si propriétaire et enleve un membre du groupe si il quitte
          * @author WATELOT Paul-Emile
          * @param $idProjet l'id du projet a supprime ou se retirer
          * @return redirection vers la page index des projets
          */
          public function delete($idProjet){
            //On récupère la table Projet pour en extraire le projet désiré
            $projetTab = TableRegistry::getTableLocator()
            ->get('Projet')->find()
            ->where(['idProjet' => $idProjet])
            ->first();

            //permet de savoir si un utilisateur est propriétaire
            $session = $this->request->getSession();
            if ($session->check('Auth.User.idUtilisateur')) {
              $idUser = $session->read('Auth.User.idUtilisateur');
              $membres = TableRegistry::getTableLocator()->get('Membre');
              //si l'utilisateur est le propriétaire on:
              if($projetTab->idProprietaire == $idUser){

                //degage tout les membres du projet
                $query = $membres->query();
                $query->delete()->where(['idProjet' => $idProjet])->execute();

                $this->supprimerToutesNotifications($idProjet);

                //supprime les taches du projet
                $taches = TableRegistry::getTableLocator()->get('Tache');
                $query = $taches->query();
                $query->delete()->where(['idProjet' => $idProjet])->execute();

                //supprime le projet
                $projets = TableRegistry::getTableLocator()->get('Projet');
                $query = $projets->query();
                $query->delete()->where(['idProjet' => $idProjet])->execute();


                //TODO pour PE: Envoyer une notif de suppression a tout les membres comme quoi le projet à été supprimé

              }
              //sinon si c'est un invité on le retire dans la table membre
              else{
                //retirer les responsabilités du membre dans le projet qu'il souhaite quitter
                $tachesSousResponsabilite = TableRegistry::getTableLocator()
                ->get('Tache')->find()
                ->where(['AND' => ['idProjet' => $idProjet, 'idResponsable' => $idUser]])
                ->all();
                foreach($tachesSousResponsabilite as $tache):
                  (new TacheController)->notSoResponsible($idProjet, $tache->idTache);
                endforeach;

                //enleve l'utilisateur du projet
                $query = $membres->query();
                $query->delete()->where(['idProjet' => $idProjet, 'idUtilisateur' => $idUser])->execute();

                //TODO pour PE: Envoyer une notif comme quoi X a quitté le projet a tout les membres
              }
            }

            return $this->redirect(['action'=> 'index']);
          }

          /**
          * Permet d'archiver un projet uniquement si il est expiré et si l'utilisateur en est le propriétaire
          * @param int $idProjet ID du projet a archiver
          * @author Pedro Sousa Ribeiro (et Diana mais juste pour l'autorisationProprietaire)
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
              if ($projet->etat == 'Expire') {
                if ($user === $projet->idProprietaire) {
                  $projet->etat = 'Archive';
                  $projet->dateArchivage = Time::now();
                  $this->Projet->save($projet);

                  // Projet archivé
                  $this->Flash->success(__("Projet archivé avec succès"));
                  $this->redirect(['action' => 'archives']);
                  }
                  // Projet non expiré
                } else {
                  $this->Flash->error(__("Le projet doit être expiré pour pouvoir l'archiver."));
                  $this->redirect($this->referer());
                }
            }
          }

          /**
          * @author Théo Roton
          * @param idProjet : id u projet que l'on veut désarchiver
          * Cette fonction permet de désarchiver un projet qui a été précédemment
          * archivé à l'aide de son id.
          * On renvoie l'utilisateur sur sa liste de projets (non archivés) une fois
          * que celui-ci est désarchivé.
          */
          public function desarchive($idProjet) {
            // On récupère le projet
            $projet = $this->Projet->get($idProjet);

            $session = $this->request->getSession();
            if ($session->check('Auth.User.idUtilisateur')) {
              $user = $session->read('Auth.User.idUtilisateur');
              if ($user === $projet->idProprietaire) {
                // On passe le projet a expiré et on enlève la date d'archivage
                $projet->etat = "Expire";
                $projet->dateArchivage = NULL;
                // On met à jour le projet
                $this->Projet->save($projet);

                // Projet désarchivé avec succès
                $this->Flash->success(__("Projet désarchivé avec succès"));
                $this->redirect(['action' => 'index']);
              } else {
                $this->Flash->error(__("Seul le/a propriétaire est en mesure de désarchiver le projet."));
                $this->redirect($this->referer());
              }
            } else {
              $this->Flash->error(__("Vous devez être connecté/e pour désarchiver un projet."));
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
            // On vérifie que l'utilisateur est bien propriétaire du projet (redirection si non)
            $this->autorisationProprietaire($id);

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

            $session = $this->request->getSession();
            $idUtilisateur = $session->read('Auth.User.idUtilisateur');

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
                $existe_deja = $projets->find()
                ->where(['idProprietaire' => $idUtilisateur])
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

                  // Si le projet était archivé ou expiré, et que la nouvelle date de fin est après aujourd'hui, on met le projet en cours
                  if (($projet->etat == 'Archive' || $projet->etat == 'Expire') && verificationDates($today, $receivedData['dateFin'])) {
                    $projet->etat = 'En cours';

                  //Sinon le projet est expiré
                  } else {
                    $projet->etat = 'Expire';
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

                // Si le projet était archivé ou expiré, alors on le met en cours
                if ($projet->etat == 'Archive' || $projet->etat == 'Expire') {
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
                $this->Flash->error(__("La description est trop longue (max 512 caractères)."));
                $erreur = true;
              }
            }

            // Si on n'a pas eu d'erreur alors
            if (!$erreur){
              // On sauvegarde les nouvelles informations du projet
              $projets->save($projet);
              // On indique que la modification a réussie
              $this->Flash->success(__('Votre projet a été modifé.'));

              //On récupère la table des notifications
              $notifications = TableRegistry::getTableLocator()->get('Notification');

              $contenu = "Le projet ".$projet->titre." a été modifié.";

              $membres = TableRegistry::getTableLocator()->get('Membre');
              $membres = $membres->find()->contain('Utilisateur')
              ->where(['idProjet' => $receivedData['id']]);

              $destinataires = array();
              foreach ($membres as $m) {
                $idUtil = $m->un_utilisateur->idUtilisateur;
                array_push($destinataires, $idUtil);
              }

              var_dump($destinataires);
              envoyerNotification(0, 'Informative', $contenu, $receivedData['id'], null, $idUtilisateur, $destinataires);

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
            $idProprietaire = $this->autorisationProprietaire($idProjet);
            $projets = TableRegistry::get('Projet');

            if ($idMembre == $idProprietaire){
              $this->Flash->set('Vous êtes déjà propriétaire.', ['element' => 'error']);
              return $this->redirect(['controller'=>'Projet', 'action'=> 'index']);
            }
            // on récupère l'ID du propriétaire
            $projet = $projets->find()->where(['idProjet'=>$idProjet])->first();
            // mise à jour du nouveau propriétaire dans la DB
            $query = $projets->query();
            $query->update()
            ->set(['idProprietaire' => $idMembre])
            ->where(['idProjet' => $idProjet])->execute();
            // redirection vers la page d'accueil des projets

            //On récupère la table des projets
            $projets = TableRegistry::getTableLocator()->get('Projet');
            $projet = $projets->find()->where(['idProjet' => $idProjet])->first();
            $nomProjet = $projet['titre'];

            //Envoie un notification au nouveau propriétaire
            envoyerNotificationProjet(0,"Vous êtes devenu le propriétaire de " .$nomProjet, $idProjet, $idMembre);

            $this->Flash->set('Le/a propriétaire a bien été modifié/e.', ['element' => 'success']);
            return $this->redirect(['controller'=>'Projet', 'action'=> 'index']);
          }

        }
        ?>
