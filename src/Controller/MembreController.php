<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'Notifications.php');


class MembreController extends AppController
{

   /**
    * Vérifie si l'utilisateur est propriétaire du projet.
    *
    * @param idProjet : id du projet.
    * @param idUtilisateur : id de l'utilisateur connecté.
    * @return Vrai si l'utilisateur est propriétaire du projet, faux s'il ne l'est pas ou s'il n'est pas connecté.
    *
    * Redirection : /
    *
    * @author POP Diana
    */
    private function estProprietaireDe($idProjet){
      // On récupère le Projet pour en extraire les informations.
      $tableProjet = TableRegistry::getTableLocator()
        ->get('Projet')->find()
        ->where(['idProjet' => $idProjet])
        ->first();

      $idUtilisateur = null;

      $session = $this->request->getSession();
      if ($session->check('Auth.User.idUtilisateur')) $idUtilisateur = $session->read('Auth.User.idUtilisateur') ;

      $estProprietaire = false;
      if($tableProjet->idProprietaire == $idUtilisateur){ $estProprietaire = true; }
      return $estProprietaire;
    }

    /**
    * Pas le droit à la surcharge avec la fonction estProprietaireDe.
    * Vérifie si l'utilisateur donné est propriétaire du projet.
    *
    * @param idProjet : id du projet.
    * @param idUtilisateur : id de l'utilisateur potentiellement propriétaire.
    * @return Vrai si l'utilisateur donné est propriétaire du projet.
    *
    * Redirection : /
    *
    * @author POP Diana
    */
    private function estProprietaire($idProjet, $idUtilisateur){
      // On récupère le Projet pour en extraire les informations.
      $tableProjet = TableRegistry::getTableLocator()->get('Projet');
      $query = $tableProjet->find()->where(['idProjet' => $idProjet, 'idProprietaire'=>$idUtilisateur])->first();

      return ($query!==null);
    }

    /**
    * Vérifie si l'utilisateur donné est membre du projet.
    *
    * @param idProjet : id du projet.
    * @param idUtilisateur : id de l'utilisateur potentiellement membre.
    * @return Vrai si l'utilisateur donné est membre du projet.
    *
    * Redirection : /
    *
    * @author POP Diana
    */
    private function estMembreDe($idProjet, $idUtilisateur){
      $count = $this->Membre->find()->where(['idUtilisateur'=>$idUtilisateur, 'idProjet'=>$idProjet])->count();
      return ($count>0);
    }

    /**
    * Vérifie si un utilisateur existe avec le pseudo donné.
    *
    * @param pseudoUtilisateur : pseudo d'un possible utilisateur.
    * @return Vrai si un utilisateur existe avec ce pseudo.
    *
    * Redirection : /
    *
    * @author POP Diana
    */
    private function existe($pseudoUtilisateur){
        $tableUtilisateurs = TableRegistry::get('Utilisateur');
        $query = $tableUtilisateurs->find()
            ->select(['idUtilisateur'])
            ->where(['pseudo' => $pseudoUtilisateur])
            ->first();

        $idUtilisateur = $query['idUtilisateur'];

        return $idUtilisateur!==null;
    }

    /**
    * Donne l'id d'une personne grâce au pseudo de cette personne.
    * Fonction à utiliser seulement si le paramètre n'est pas null.
    *
    * @param pseudoUtilisateur : pseudo d'un utilisateur.
    * @return idUtilisateur : id de l'utilisateur avec le pseudo donné.
    *
    * Redirection : /
    *
    * @author POP Diana
    */
    private function getIdDe($pseudoUtilisateur){
      $tableUtilisateurs = TableRegistry::get('Utilisateur');
      $query = $tableUtilisateurs->find()
          ->select(['idUtilisateur'])
          ->where(['pseudo' => $pseudoUtilisateur])
          ->first();

        $idUtilisateur = $query['idUtilisateur'];

        return $idUtilisateur;
    }

    /**
    * Crée un nouveau membre et le sauvegarde dans la base de données.
    * Affiche un message flash pour succès ou échec.
    *
    * @param idUtilisateur : id de l'utilisateur à ajouter comme membre.
    * @param idProjet : id du projet auquel ajouter l'utilisateur comme membre.
    * @return Vrai si l'utilisateur a bien été sauvegardé comme membre du projet.
    *
    * Redirection : /
    *
    * @author POP Diana
    */
    private function sauvegarderMembre($idUtilisateur, $idProjet){
      $membre = $this->Membre->newEntity();
      $membre->idUtilisateur= $idUtilisateur;
      $membre->idProjet= $idProjet;

      if ($estSauvegarde = $this->Membre->save($membre)) {
        $this->Flash->success(__('Le membre a été ajouté à la liste.'));

      // S'il y a eu une erreur lors de la sauvegarde du membre.
      }else{
        $this->Flash->error(__('Impossible d\'ajouter ce membre.'));
    }
    return $estSauvegarde;
  }

    private function supprimerMembre($idUtilisateur, $idProjet){
      $tableTaches = TableRegistry::get('Tache');

      // Le membre n'est plus responsable d'aucune tâche du projet.
      $tableTaches->updateAll(array('idResponsable' => NULL), ['idProjet'=>$idProjet, 'idResponsable' => $idUtilisateur]);

      // Maintenant, on peut supprimer le membre du projet.
      $membre = $this->Membre->find()->where(['idUtilisateur'=>$idUtilisateur, 'idProjet'=>$idProjet])->first();

      if ($this->Membre->delete($membre)){
        $this->Flash->set('Le membre a été supprimé du projet.', ['element' => 'success']);
      }else{
        $this->Flash->set('Impossible de supprimer ce membre.', ['element' => 'success']);
      }
    }

  /**
  * Si l'utilisateur n'est pas le propriétaire du projet, il sera redirigé vers l'accueil avec un message d'erreur.
  *
  * @param idProjet : id du projet.
  * @return /
  *
  * Redirection : (si non accès) index de Accueil.
  *
  * @author POP Diana
  */
  private function autorisation($idProjet){
    // On vérifie si l'utilisateur est propriétaire du projet.
    $estProprietaire = $this->estProprietaireDe($idProjet);

    // On procède à la vérification.
    if ($estProprietaire==false){
      $this->Flash->error(__('Ce projet n\'existe pas ou vous n\'y avez pas accès.'));
      $this->redirect(['controller'=>'Accueil', 'action'=>'index', $idProjet]);
    }
  }

  /**
  * Affiche les membres d'un projet (le propriétaire est considéré comme un membre et est donc aussi affiché).
  * La fonction vérifie si l'utilisateur a accès au projet à l'id donné en argument.
  *
  * @param idProjet : id du projet.
  * @return /
  *
  * Redirection : (si non accès) index de Accueil.
  * @author POP Diana
  */
    public function index($idProjet){
      $this->autorisation($idProjet);
      $this->loadComponent('Paginator');

      $invites = array();
      $session = $this->request->getSession();
      $membres = $this->Paginator->paginate($this->Membre->find()
          ->contain(['Utilisateur'])
          ->where(['idProjet' => $idProjet]));
      $notifications = TableRegistry::getTableLocator()->get('Notification');
      $vuesNotifications = TableRegistry::getTableLocator()->get('VueNotification');
      $utilisateurs = TableRegistry::getTableLocator()->get('Utilisateur');
      $invitations = $notifications->find()->where(['type' => 'Invitation', 'idProjet' => $idProjet]);

      foreach ($invitations as $invitation){
        $idNotification = $invitation->idNotification;

        $existeMembre = $vuesNotifications->find()->where(['idNotification'=>$idNotification, 'etat' => 'En attente'])->count();

        if($existeMembre > 0){

          $invitationAuMembre = $vuesNotifications->find()->where(['idNotification'=>$idNotification, 'etat' => 'En attente'])->first();

          $pseudoMembre = $utilisateurs->find()->where(['idUtilisateur' => $invitationAuMembre->idUtilisateur])->first();

          array_push($invites, $pseudoMembre);

        }
      }

      $projets = TableRegistry::getTableLocator()->get('Projet');
      $projet = $projets->find()->where(['idProjet' => $idProjet])->first();
      $titreProjet = $projet['titre'];
      $this->set(compact('membres', 'idProjet', 'titreProjet', 'invites'));
    }


    /**
    * Vérifie si une invitation a déjà été envoyée au membre.
    *
    * @param idUtilisateur : id de l'utilisateur à inviter
    * @param idProjet : id du projet dans lequel on veut l'inviter
    * @return Vrai si une invitation a déjà été envoyée
    *
    * @author Pop Diana
    */
    private function estDejaInvite($idUtilisateur, $idProjet){
      $resultat = false;
      $notifications = TableRegistry::getTableLocator()->get('Notification');
      $vuesNotifications = TableRegistry::getTableLocator()->get('VueNotification');

      $invitations = $notifications->find()->where(['type' => 'Invitation', 'idProjet' => $idProjet]);
      foreach ($invitations as $invitation){
        $idNotification = $invitation->idNotification;

        $invitationsAuMembre = $vuesNotifications->find()->where(['idNotification'=>$idNotification, 'idUtilisateur' => $idUtilisateur]);
        foreach ($invitationsAuMembre as $invitationAuMembre){
          debug($invitationAuMembre);
          if (!$invitationAuMembre->etat == 'En attente') $resultat = true;
        }
      }

      return $resultat;
    }

    /**
    * Ajoute un membre dans le projet.
    * Fonction appelée au clic sur "Inviter" dans le index.ctp de ce controller.
    *
    * La fonction vérifie avant l'ajout :
    *       - Si l'utilisateur n'existe pas
    *       - Si l'utilisateur est pas déjà membre de cette liste
    *       - Si l'utilisateur est propriétaire du projet.
    *
    * Si l'un de ces critères est vrai, alors le membre n'est pas ajouté dans le projet.
    *
    * @param idProjet : l'id du projet (les autres informations nécessaires viennent d'un POST).
    * @return /
    *
    * Redirection : index de ce controller.
    *
    * @author Pop Diana
    */
    public function add($idProjet){
      $this->autorisation($idProjet);

      if ($this->request->is('post')){
        /* Initialisation du pseudo et de l'id de l'utilisateur à ajouter. */
        $pseudoUtilisateur = $this->request->getData()['recherche_utilisateurs'];
        $idUtilisateur = null;

        /* Initialisation de variables pour vérifications. */
        // Est-ce que l'utilisateur à ajouter existe ?
        $existeUtilisateur = $this->existe($pseudoUtilisateur);

        if ($existeUtilisateur){
          $idUtilisateur = $this->getIdDe($pseudoUtilisateur);
        }

        // Est-ce que l'utilisateur à ajouter est le propriétaire ?
        $estProprietaire = $this->estProprietaire($idProjet, $idUtilisateur);

        // Est-ce que l'utilisateur à ajouter est déjà membre ?
        $estDejaMembre = $this->estMembreDe($idProjet, $idUtilisateur);

        // Est-ce que l'utilisateur à ajouter a déjà été invité ?
        $estDejaInvite = $this->estDejaInvite($idUtilisateur, $idProjet);

        // Si la personne à ajouter existe, qu'elle n'est pas le propriétaire et qu'elle n'est pas déjà membre, on l'ajoute à la liste de membres.
        if ($existeUtilisateur && !$estProprietaire && !$estDejaMembre && !$estDejaInvite){

          //On récupère la table des projets
          $projets = TableRegistry::getTableLocator()->get('Projet');
          $projet = $projets->find()->where(['idProjet' => $idProjet])->first();
          $nomProjet = $projet['titre'];

          $destinataires = array();
          //On met l'utilisateur invité en tant que destinataire
          array_push($destinataires, $idUtilisateur);

          //On get la session pour avoir l'id de l'expediteur
          $session = $this->request->getSession();
          //On récupère l'id de la session
          $idSession = $session->read('Auth.User.idUtilisateur');

          //On remplit le contenu de la notification
          $contenu = $session->read('Auth.User.pseudo') . " vous a demandé de rejoindre son projet '" . $nomProjet ."'.";

          //Envoie une notification à un utilisateur pour lui demander de rejoindre son projet
          envoyerNotification(1, 'Invitation', $contenu, $idProjet, null, $idSession, $destinataires);

          $this->Flash->success(__('Une invitation a été envoyée à ce membre.'));

        // Si les vérifications ont été fausses, on affiche les messages d'erreur selon les cas.
        }else{
          if(!$existeUtilisateur) $this->Flash->error(__('Ce membre n\'existe pas.'));

          if ($estProprietaire) $this->Flash->error(__('Vous êtes le/a propriétaire de ce projet et faites donc déjà partie de ce projet.'));

          if ($estDejaMembre && !$estProprietaire) $this->Flash->error(__('Ce membre est déjà dans le projet.'));

          if ($estDejaInvite) $this->Flash->error(__('Ce membre a déjà reçu une invitation.'));

      }// Fin messages des verifications

        // Peu importe le cas, on est redirigé vers l'index.
        $this->redirect(['controller'=>'Membre', 'action'=> 'index', $idProjet]);
      } // Fin if post
    }

    /**
    * Supprime un membre du projet.
    *
    * La fonction vérifie avant la suppression :
    *       - Si l'utilisateur est propriétaire du projet .
    * Si ce critère est vrai, alors le membre n'est pas supprimé du projet.
    *
    * @param: $id_utilisateur correspond à l'idUtilisateur et $id_projet correspond à l'idProjet.
    * @return /
    *
    * Redirection : index de ce controller.
    *
    * @author Pop Diana, Rossi Djessy
    */
    public function delete($idUtilisateur, $idProjet){
      $this->autorisation($idProjet);

      // Est-ce que l'utilisateur à supprimer est membre de ce projet ?
      $estMembre = $this->estMembreDe($idProjet, $idUtilisateur);

      // Est-ce que l'utilisateur à supprimer est propriétaire de ce projet ?
      $estProprietaire = $this->estProprietaire($idProjet, $idUtilisateur);

      // Si l'utilisateur à supprimer du projet n'en est pas propriétaire, on peut le supprimer.
      if (!$estProprietaire && $estMembre){

        //On récupère la table des projets
        $projets = TableRegistry::getTableLocator()->get('Projet');
        $projet = $projets->find()->where(['idProjet' => $idProjet])->first();
        $nomProjet = $projet['titre'];

        /* NOTIFICATION */
        $destinataires = array();
        //On met l'utilisateur invité en tant que destinataire
        array_push($destinataires, $idUtilisateur);

        //On get la session pour avoir l'id de l'expediteur
        $session = $this->request->getSession();
        //On récupère l'id de la session
        $idSession = $session->read('Auth.User.idUtilisateur');

        //On remplit le contenu de la notification
        $contenu = $session->read('Auth.User.pseudo')." vous a exclu(e) du projet '" . $nomProjet ."'.";

        //Envoie une notification à un utilisateur pour le notifier qu'il a été exclu du projet
        envoyerNotification(0, 'Informative', $contenu, $idProjet, null, $idSession, $destinataires);

        $this->supprimerMembre($idUtilisateur, $idProjet);
      // Si l'utilisateur à supprimer du projet en est le propriétaire, on ne peut pas le supprimer.
      }else if($estProprietaire){
          $this->Flash->set('Vous êtes propriétaire de ce projet.', ['element' => 'error']);

      // Si l'utilisateur à supprimer du projet n'en est pas membre, on ne peut pas le supprimer (empêche une grosse erreur avec delete(entity) de CakePhp).
      }else{
        $this->Flash->set('Cet utilisateur/trice n\'est pas membre du projet.', ['element' => 'error']);
      }

      // Dans tous les cas, on redirige à l'index.
      $this->redirect(['controller'=>'Membre', 'action'=> 'index', $idProjet]);
    }

}

?>
