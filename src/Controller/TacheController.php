<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\I18n\Time;

require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'VerificationChamps.php');
require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'listeErreursVersString.php');
require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'Notifications.php');

class TacheController extends AppController
{
  /**
  * Affichage d'un projet avec sa liste de tâches (en fonction de l'id donnée).
  * Redirige vers l'accueil si le projet n'existe pas ou si la personne n'en est pas membre sinon affiche la liste des tâches.
  * @author Thibault Choné, POP Diana
  * @param  int $idProjet id du projet à afficher
  */
  public function index($idProjet)
  {
    $estProprietaire = false;
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
        $user = $session->read('Auth.User.idUtilisateur');
    }

    $projetTab = TableRegistry::getTableLocator() // On récupère la table Projet pour en extraire les infos
        ->get('Projet')->find()
        ->where(['idProjet' => $idProjet])
        ->first();

    // Pour la couronne dans le header
    Configure::write('utilisateurProprietaire', false);
    //Pour le nom en rouge quand un projet est expiré
    Configure::write('estExpire', false);

    $today = Time::now();
    if($projetTab->dateFin < $today && $projetTab->dateFin != null){
      Configure::write('estExpire', true);
    }

    $this->loadComponent('Paginator');

    $taches = $this->Paginator->paginate($this->Tache->find()
        ->contain('Utilisateur')
        ->where(['idProjet' => $idProjet])
        ->order(['finie' => 'ASC', 'Tache.titre' => 'ASC']));

    // Regarde si l'utilisateur est autorisé à acceder au contenu
    $estProprietaire = $this->autorisation($idProjet);

    // fin session check idUtilisateur
    $this->set(compact('taches', 'idProjet', 'projetTab', 'estProprietaire', 'user'));
  }

  /**
   * Cette fonction affiche les détails, la description et les membres,
   * du projet identifié par son id.
   * @param idProjet : id du projet pour lequel on affiche les détails
   *
   * @author Thibault Choné, Théo Roton
   */
  public function details($idProjet)
  {
    $this->autorisation($idProjet);

    //On récupère la table des projets
    $projets = TableRegistry::getTableLocator()->get('Projet');
    //On récupère le projet identifié par idProjet
    $projet = $projets->find()
        ->where(['idProjet' => $idProjet])
        ->first();

    //On récupère la description du projet
    $desc = $projet->description;
    $titre = $projet->titre;

    //On récupère la table des membres
    $membres = TableRegistry::getTableLocator()->get('Membre');
    //On récupère les membres du projet identifié par idProjet
    $membres = $membres->find()
        ->contain('Utilisateur')
        ->where(['idProjet' => $idProjet]);

    //On met tout les membres dans un tableau
    $mbs = array();
    foreach ($membres as $m) {
      array_push($mbs, $m->un_utilisateur->pseudo);
    }

    $this->set(compact('desc', 'idProjet', 'mbs', 'titre'));
  }

  /**
   * Cette méthode permet d'ajouter une tâche à un projet
   *
   * @param idProjet id du projet dans lequel ajouter la tâche
   * @author Clément Colné, Adrien Palmieri
   */
  public function add($idProjet){
    if ($this->request->is('post')) {
      $data = $this->request->getData();

      $data['idProjet'] = $idProjet;
      $data['titre'] = nettoyerTexte($data['titre']);
      $data['description'] = nettoyerTexte($data['description']);

      $tache = $this->Tache->newEntity($data);

      if(!empty($tache->errors()) && $tache->errors() != null){
        $errors = listeErreursVersString($tache->errors(), $this);
        $this->Flash->error(
          __("Erreurs : ".implode("\n \r", $errors))
        );
      }else{

        $tache->finie = 0;
        $tache->idProjet = $idProjet;

        if(empty($tache->titre)){
          $this->Flash->error(__('Impossible d\'ajouter une tâche avec un nom vide.'));
        }else{
          // On verifie qu'il n'existe pas une tache du meme nom
          foreach($this->Tache->find('all', ['conditions' => ['idProjet' => $idProjet]]) as $task) {
            if($task->titre == $tache->titre) {
              $this->Flash->error(__('Impossible d\'avoir plusieurs tâches avec le même nom dans un même projet.'));
              return $this->redirect(['action'=> 'add', $idProjet]);
            }
          }

          if ($this->Tache->save($tache)) {
            $this->Flash->success(__('Votre tâche a été sauvegardée.'));
            if($tache->estResponsable == 1) {
              // l'utilisateur devient responsable de la tâche
              $this->devenirResponsable($idProjet, $tache->idTache);
            }
            return $this->redirect(['action'=> 'index', $idProjet]);
          }else{
            $this->Flash->error(__('Impossible d\'ajouter votre tâche.'));
          }
        }
      }
    }

    $projets = TableRegistry::getTableLocator()->get('Projet');
    $projet = $projets->find()
        ->where(['idProjet' => $idProjet])
        ->first();
    $titreProjet = $projet['titre'];

    $this->set(compact('idProjet', 'titreProjet'));
  }

  /**
  * Affiche toutes les tâches de l'utilisateur
  * @author Pedro Sousa Ribeiro
  * Redirection: Si l'utilisateur n'est pas connecté, il est redirigé vers la page d'où il vient.
  *              Sinon il est dirigé vers la liste de ses tâches
  */
  public function my() {
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      $taches = $this->Tache->find()
          ->contain(['Utilisateur', 'Projet'])
          ->where(['idResponsable' => $user])
          ->order(['finie' => 'ASC', 'Tache.titre' => 'ASC'])
          ->toArray();

      $this->set(compact('taches'));
    } else {
      $this->Flash->error(_('Une erreur est survenue lors de la récupération des tâches.'));
      $this->redirect($this->referer());
    }
  }

  /**
   * Utilisée dans : Template/Tache/index.ctp
   * Affiche la page de modification de tâche et traite le formulaire de modification (et le push dans la bdd en cas de succès)
   * Redirect vers la liste des projets si il y a eu une modification effective.
   * @author Thibault Choné
   * @param  int $idProjet id du projet dans lequel se trouve la tâche
   * @param  int $idTache  id de la tâche à modifier
   * @return redirect      Si la modification est effectuée sans erreur
   */
  public function edit($idProjet, $idTache)
  {
    $data = $this->request->getData();

    $tache = $this->Tache->find()
        ->where(['idTache' => $idTache])
        ->first();

    $succes = false;

    if(!empty($data)){ //Dans le cas d'arrivée
      if(empty($data['titre'])){
        $this->Flash->error(__("Le nom de la tâche ne peut pas être vide."));
      }else{
        $data['titre'] = nettoyerTexte($data['titre']);
        $data['description'] = nettoyerTexte($data['description']);

        //Ici on unset titre cas cela permet d'éviter d'avoir une erreur de même nom de tâche
        if($tache['titre'] == $data['titre']){
          unset($data['titre']);
        }

        $data = array_filter($data, function($value) { return !is_null($value) && $value !== '' && !empty($value); }); //On supprime les éléments vide du tableau

        $data['idProjet'] = $idProjet;

        //$tache = $this->Tache->get($idTache); //On récupère les données tâches
        $data2 = $this->Tache->patchEntity($tache, $data); //On "assemble" les données entre data et une tâche

        if($this->Tache->save($data2)){ //On sauvegarde les données (Le vérificator passe avant)

          //On récupère l'id session pour connaitre l'expediteur
          $session = $this->request->getSession();
          if ($session->check('Auth.User.idUtilisateur')) {
              $user = $session->read('Auth.User.idUtilisateur');
          }


          //On récupère les membres du projet afin de les notifier
          $membres = TableRegistry::getTableLocator()->get('Membre')->find()
              ->contain('Utilisateur')
              ->where(['idProjet' => $idProjet]);


          //On récupère les id des membres du projet
          $destinataires = array();
          foreach ($membres as $m) {
            $idUtil = $m->un_utilisateur->idUtilisateur;
            array_push($destinataires, $idUtil);
          }

          unset($destinataires[array_search($user, $destinataires)]);

          //On ajoute le contenu de la notification
          $contenu = "La tâche " . $tache->titre . " a été modifiée.";

          //On appelle la fonction pour envoyer la notification
          envoyerNotification(0, 'Informative', $contenu, $idProjet, null, $user, $destinataires);

          $this->Flash->success(__('La tâche a été modifiée.'));
          $succes = true;
        }else{
          //On affiche les erreurs
          $errors = listeErreursVersString($tache->errors(), $this);
          if(!empty($errors)){
            $this->Flash->error(
              __("Erreurs : ".implode("\n \r", $errors))
            );
          }
        }
      }
    }

    $titre = $tache['titre'];
    $description = $tache['description'];

    //On récupère le titre du projet en cours
    $projets = TableRegistry::getTableLocator()->get('Projet');
    $projet = $projets->find()
        ->where(['idProjet' => $idProjet])
        ->first();
    $titreProjet = $projet['titre'];


    $this->set(compact('idProjet', 'idTache', 'titre', 'description', 'titreProjet'));

    if($succes){
      return $this->redirect(['action'=> 'index', $idProjet]);
    }
  }

  /**
   * Permet à un membre de projet de devenir responsable d'une tache
   * @param $idProjet int identifiant unique du projet
   * @param $idTache int identifiant unique de la tâche
   * @return \Cake\Http\Response|null Retourne sur la liste des tâches
   * @author Mathieu TABARY
   */
  public function devenirResponsable($idProjet, $idTache) {
    $session = $this->request->getSession();
    $user = $session->read('Auth.User.idUtilisateur');

    $tache = $this->Tache->get($idTache);
    $tache->idResponsable = $user;
    $this->Tache->save($tache);

    //On ajoute le contenu de la notification
    $contenu = $session->read('Auth.User.pseudo') . " est devenu(e) responsable de la tâche - " . $tache->titre;

    //On récupère les membres du projet afin de les notifier
    $membres = TableRegistry::getTableLocator()->get('Membre');
    $membres = $membres->find()
        ->contain('Utilisateur')
        ->where(['idProjet' => $idProjet]);

    //On récupère les id des membres du projet
    $destinataires = array();
    foreach ($membres as $m) {
      $idUtil = $m->un_utilisateur->idUtilisateur;
      array_push($destinataires, $idUtil);
    }

    unset($destinataires[array_search($user, $destinataires)]);

    //On appelle la fonction pour envoyer la notification
    envoyerNotification(0, 'Informative', $contenu, $idProjet, null, $user, $destinataires);

    return $this->redirect(['action' => 'index', $idProjet]);
  }

  /**
  * Utilisée dans Template/Tache/index.ctp
  * lors de la suppression d'une tâche.
  * @author WATELOT Paul-Emile
  * @param $idProjet l'id du projet qui contient la tache, $idTache l'id de la tache a supprimer
  * @return redirection vers la page du projet
  */
  public function delete($idProjet, $idTache){
    //donne acces a (Tache/)index.ctp
    $this->set(compact('idProjet','idTache'));

    //On récupère la table Projet et on recupere le projet voulu
    $projetTab = TableRegistry::getTableLocator()
        ->get('Projet')->find()
        ->where(['idProjet' => $idProjet])
        ->first();

    //permet de savoir si un utilisateur est propriétaire du projet
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      $tacheTab = TableRegistry::getTableLocator()->get('Tache');
      $tache = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
          ->get('Tache')->find()
          ->where(['idTache' => $idTache])
          ->first();

      //si il est propriétaire du projet ou que l'utilisateur est responsable de la tache il peut supprimer cette tache
      if($projetTab->idProprietaire == $user || $tache->idResponsable == $user){

        //Si c'est le proprio envoyer une notif a tout les membres du projet comme quoi la tache X du projet Y a ete supprimée.
        if($projetTab->idProprietaire == $user){
          // On récupère les tables nécessaires à l'opération
          $notifications = TableRegistry::getTableLocator()->get('Notification');
          $vueNotifications = TableRegistry::getTableLocator()->get('VueNotification');
          $taches = TableRegistry::getTableLocator()->get('Tache');

          // On récupère les notifications liés à la tâche pour les supprimer
          $notifications_supprs = $notifications->find()->contain('VueNotification')
              ->where(['idTache' => $idTache])
              ->toArray();

          // Pour chaque notification
          foreach ($notifications_supprs as $not) {
            // Pour chaque vue d'une notification
            foreach ($not->notifications as $vue) {
              // On supprime la vue
              $vueNotifications->delete($vue);
            }
            // On supprime la notification
            $notifications->delete($not);
          }

          // On récupère la tâche à supprimer
          $tache = $taches->find()
              ->where(['idTache' => $idTache])
              ->first();


          //On crée une nouvelle notification pour le projet courant
          $contenu = "La tâche " . $tache->titre . " a été supprimée.";

          //On récupère les membres du projet
          $membres = TableRegistry::getTableLocator()->get('Membre');
          $membres = $membres->find()
              ->contain('Utilisateur')
              ->where(['idProjet' => $idProjet]);

          //Pour chaque membre du projet, on envoie une notification à celui-ci
          $destinataires = array();
          foreach ($membres as $m) {
            $idUtil = $m->un_utilisateur->idUtilisateur;
            array_push($destinataires, $idUtil);
          }
          unset($destinataires[array_search($user, $destinataires)]);

          $taches->delete($tache);
          $this->Flash->success(__('La tache a été supprimée'));  

          envoyerNotification(0, 'Informative', $contenu, $idProjet, null, $user, $destinataires);
        } else {
          //sinon envoyer une demande de confirmation au proprio et si il accepte, la supprimer
          //On crée une nouvelle notification pour le projet courant
          $contenu = $session->read('Auth.User.pseudo')." veut supprimer la tâche ".$tache->titre." du projet ".$projetTab->titre.".";

          envoyerNotification(1, 'Suppression', $contenu, $idProjet, $idTache, $user, array($projetTab->idProprietaire));

          $this->Flash->default(__('Une demande pour supprimer cette tâche à été envoyée au/à la propriétaire.'));
        }
      }
    }
    return $this->redirect(['action' => 'index', $idProjet]);
  }

  /**
  * Permet a un membre du projet de se retirer d'une tâche
  *
   * @param $idProjet l'id du projet qui contient la tâche de laquelle on souhaite se retirer
   * @param $idTache l'id de la tache dont on souhaite se retirer
   * @return redirection vers la page du projet
   * @author Adrien Palmieri
  */
  public function notSoResponsible($idProjet, $idTache) {
    $tache = $this->Tache->get($idTache);
    $tache->idResponsable = NULL;
    $tache->finie = 0;
    $this->Tache->save($tache);
    $session = $this->request->getSession();
    $user = $session->read('Auth.User.idUtilisateur');

    //On ajoute le contenu de la notification
    $contenu =  $session->read('Auth.User.pseudo') . " n'est plus responsable de la tâche - " . $tache->titre;

    //On récupère les membres du projet afin de les notifier
    $membres = TableRegistry::getTableLocator()->get('Membre');
    $membres = $membres->find()->contain('Utilisateur')
    ->where(['idProjet' => $idProjet]);

    //On récupère les id des membres du projet
    $destinataires = array();
    foreach ($membres as $m) {
      $idUtil = $m->un_utilisateur->idUtilisateur;
      array_push($destinataires, $idUtil);
    }
    unset($destinataires[array_search($user, $destinataires)]);

    //On appelle la fonction pour envoyer la notification
    envoyerNotification(0, 'Informative', $contenu, $idProjet, null, $user, $destinataires);

    return $this->redirect(['action' => 'index', $idProjet]);
  }

  /**
  * Permet de changer l'état d'une tache de "fait" a "non fait" et vis versa. Cette méthode est utilisé par le script JS en Ajax
  * @param int $id ID de la tache dont l'etat est a changer
  * @param boolean $fait Booleen indiquant si la tache est faite ou non
  * @author Pedro Sousa Ribeiro
  *
  * Redirection: aucune
  */
  public function changerEtat($id, $fait) {
    // Desactive le rendu de la vue (pas besoin de la vue)
    $this->autoRender = false;
    $this->render(false);

    $tache = $this->Tache->get($id);
    $idProjet = $tache->idProjet;
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      if ($tache->idResponsable === $user) {
        if ($fait) {
          $tache->finie = 1;

          //On ajoute le contenu de la notification
          $contenu = "La tâche " . $tache->titre . " a été terminée.";

          //On récupère les membres du projet afin de les notifier
          $membres = TableRegistry::getTableLocator()->get('Membre');
          $membres = $membres->find()
              ->contain('Utilisateur')
              ->where(['idProjet' => $idProjet]);

          //On récupère les id des membres du projet
          $destinataires = array();
          foreach ($membres as $m) {
            $idUtil = $m->un_utilisateur->idUtilisateur;
            array_push($destinataires, $idUtil);
          }
          unset($destinataires[array_search($user, $destinataires)]);

          //On appelle la fonction pour envoyer la notification
          envoyerNotification(0, 'Informative', $contenu, $idProjet, null, $user, $destinataires);

        } else {
          $tache->finie = 0;
        }

        $this->Tache->save($tache);
      } else {
        $this->Flash->error(__('Seul le/a responsable de la tâche peut changer l\'état de celle-ci.'));
      }
    } else {
      $this->Flash->error(__('Vous devez être connecté/e pour changer l\'état d\'une tâche.'));
    }
  }

  /**
   * Methode permettant de verifier si l'utilisateur souhaitant acceder au projet en fait partie
   * @param $idProjet id du projet auquel l'utilisateur souhaite acceder
   * @return boolean vrai si l'utilisateur a un acces au projet
   * @return redirection vers la page d'accueil si l'utilisateur n'a pas les droits pour accéder au projet
   * @author A REMPLIR PAR LE/LES AUTEURS
   */
  private function autorisation($idProjet){

    $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
    ->get('Projet')->find()
    ->where(['idProjet' => $idProjet])
    ->first();

    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      if($projetTab->idProprietaire == $user){
        //Pour la couronne dans le header
        Configure::write('utilisateurProprietaire', true);
        return true;
        // S'il n'est pas propriétaire, est-il membre ?
        // -> Vérifie en même temps si le projet existe.
      }else{
        $membres = TableRegistry::get('Membre');
        $query = $membres->find()
            ->select(['idUtilisateur'])
            ->where(['idUtilisateur' => $user, 'idProjet' => $idProjet])
            ->count();

        // S'il n'est pas membre non plus, on le redirige.
        if ($query == 0){
          $this->Flash->error(__('Ce projet n\'existe pas ou vous n\'y avez pas accès.'));
          return $this->redirect(['controller'=>'Accueil', 'action'=>'index']);
        }
      }
    }
  }

}

?>
