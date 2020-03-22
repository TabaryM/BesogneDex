<?php
namespace App\Controller;
require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'Notifications.php');
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;


class NotificationController extends AppController
{

  /**
  * Vérifie si l'utilisateur peut accéder aux notifications.
  * Si l'utilisateur n'est pas connecté, alors il ne peut pas y accéder.
  * @return id de l'utilisateur s'il est connecté
  * Redirection vers la page précédente sinon.
  * @author POP Diana, SOUSA RIBIERO Pedro
  */
  private function autorisation(){
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')){
      return $session->read('Auth.User.idUtilisateur');
    }else{
      $this->Flash->error(_('Vous devez être connecté/e pour accéder à vos notifications.'));
      $this->redirect($this->referer());
    }
  }

  /**
  * Les notifications non-vues et non à valider deviennent vues lorsque l'utilisateur va voir ses notifications.
  * La fonction est appelée par index() de ce controller.
  * Un simple update ne convient pas car il est nécessaire d'aller chercher l'attribut "a_valider" dans une autre table (tables NotificationProjet et NotificationTache).
  *
  * @param idUtilisateur : id de l'utilisateur connecté
  *
  * @return /
  *
  * Redirection : /
  *
  * @author POP Diana, ROTON Théo
  */
  private function updateNotificationsVues($idUtilisateur){
    $tableNotifications = TableRegistry::getTableLocator()->get('VueNotification');
    $notificationsVues = $tableNotifications->find()->contain('Notification')->where(['idUtilisateur'=>$idUtilisateur, 'a_valider'=>0])->toArray();
    if ($notificationsVues){
      foreach($notificationsVues as $notif){
        $notif->vue = 1;
        $tableNotifications->save($notif);
      }
    }
  }

  /**
   * Affiche les notifications de l'utilisateur et met à jour la BDD pour dire que les notifications ont été vues.
   * La fonction est appelée au clic sur la cloche de notification depuis n'importe quelle page où l'utilisateur est connecté.
   * Les notifications à valider sont affichées en premier, puis sont par date décroissante.
   * Les notifications non vues par l'utilisateur (= sont apparues depuis la dernière fois qu'il a cliqué sur la cloche) sont affichées en gras.
   *
   * @return /
   *
   * Redirection : si l'utilisateur n'est pas connecté, renvoie à la page d'inscription.
   * @author POP Diana, SOUSA RIBIERO Pedro, ROTON Théo
   */
  public function index(){
    $idUtilisateur= $this->autorisation();

    // Initialisation des tables
    $tableNotifications = TableRegistry::getTableLocator()->get('VueNotification');
    // Récupération des notifications
    $notifs = $tableNotifications->find()->contain('Notification')->where(['idUtilisateur' => $idUtilisateur])->toArray();

    // On trie l'array résultante. Le tri est déjà sur la date, puis sur si la notification est à valider.
    $notifs = Hash::sort($notifs, '{n}.une_notification.Date','asc');
    $notifs = Hash::sort($notifs, '{n}.une_notification.a_valider', 'desc');

    // On met à jour les notifications vues seulement après leur affichage.
    $this->updateNotificationsVues($idUtilisateur);


    // Donne aux ctp les variables nécessaires
    $this->set(compact('notifs'));

  }

    /**
     *
     * Méthode permettant a l'utilisateur de refuser une notification a valider.
     *
     * La méthode recherche la notification dans la table VueNotificationProjet puis change
     * son etat pour indiquer que l'utilisateur a donné une réponse négative a celle-ci.
     *
     * Si la notification n'existe pas ou n'a pas le statut 'en attente' (cela veut dire
     * que l'utilisateur a déjà accepté ou refusée), celle-ci renvoie une erreur.
     *
     * @param $idNotifProjet : id du projet dans la table VueNotificationProjet
     * @author PALMIERI Adrien, ROSSI Djessy, POP Diana (a_valider à 0)
     */
    public function decline($idNotifProjet) {
        $idUtilisateur = $this->autorisation(); // On récupère l'id utilisateur (et verifie si il est tjrs connecté)
        $vueNotificationProjetTable = TableRegistry::getTableLocator()->get('VueNotification');
        $notificationProjet = $vueNotificationProjetTable->find()
        ->where(['idUtilisateur' => $idUtilisateur, 'idNotification' => $idNotifProjet])
        ->first();
        $projets = TableRegistry::getTableLocator()->get('Projet');
        $session = $this->request->getSession();

        /* On doit mettre l'attribut 'a_valider' de la notification à 0. */
        $notifications = TableRegistry::getTableLocator()->get('Notification');
        $notification = $notifications->find()->where(['idNotification'=>$idNotifProjet])->first();

        if($notificationProjet && $notification) { // Si la notification existe
            if($notificationProjet->etat == 'En attente') { // S'il n'a pas déjà répondu a la notif
                $notificationProjet->vue = 1; // La notification a ete vue puisqu'il a repondu
               $notificationProjet->etat = 'Refusé'; // Il refuse la notification
               $notification->a_valider = 0;
               $vueNotificationProjetTable->save($notificationProjet); // On sauvegarde les changements
               $notifications->save($notification);
               // TODO || Voir avec les gens du front pour qu'ils mettent juste à jour l'interface
               // TODO || quand on a répondu a une notif au lieu de faire un flash
               //On récupère le projet concerné pour le nom

               $projet = $projets->find()->where(['idProjet' => $notification->idProjet])->first();

               // On récupère les informations de la tâche pour envoyer une notification
               $contenu = $session->read('Auth.User.pseudo') . " a refusé votre invitation à rejoindre le projet '" . $projet['titre']."'.";

               // Pour chaque membre du projet, on envoie une notification à celui-ci
               $destinataires = array();
               array_push($destinataires, $notification->idExpediteur);

               // On envoie une notification à tous les membres du projet de la tâche supprimer
               envoyerNotification(0, 'Informative', $contenu, $idProjet, null, $notification->idExpediteur, $destinataires);

                $this->Flash->success(__('Vous avez répondu à la notification.'));
            } else {
                $this->Flash->error(__("Vous avez déjà répondu à cette notification."));
            }
        } else {
            $this->Flash->error(__("La notification à laquelle vous essayez d'accéder n'existe pas."));
        }
        $this->redirect($this->referer());
    }


    /**
    * Accepte une notification et exécute l'action selon son type.
    *
    * @param idVueNotification : id de la vue notification à laquelle on répond.
    *
    * @author Clément Colne, Diana Pop
    */
    public function accept($idVueNotification) {
        $resultat = 1;
        $idUtilisateur = $this->autorisation(); // On récupère l'id utilisateur (et verifie si il est tjrs connecté)

        $notifications = TableRegistry::getTableLocator()->get('Notification');
        $vuesNotifications= TableRegistry::getTableLocator()->get('VueNotification');

        $vueNotification = $vuesNotifications
        ->find()
        ->where(['idUtilisateur' => $idUtilisateur, 'idNotification' => $idVueNotification])
        ->first();

        $notification = $notifications
        ->find()
        ->where(['idNotification'=>$idVueNotification])
        ->first();

        // Si la notification existe
        if($notification && $vueNotification) {
            $type = $notification->type;
            $etat = $vueNotification->etat;

             // Si l'utilisateur n'a pas déjà répondu à la notification
            if($etat == 'En attente') {
              if ($type=='Proprietaire') $resultat = $this->proprietaire($idUtilisateur, $notification);
              if ($type=='Invitation') $resultat = $this->invitation($idUtilisateur, $notification);
              if ($type=='Supression') $resultat = $this->accepterSuppressionTache($idVueNotification);

              /* Changements de la vue notification */
              $vueNotification->vue = 1; // La vue notification est vue
              $vueNotification->etat = 'Accepté';
              $notification->a_valider = 0;
              $vuesNotifications->save($vueNotification);
              $notifications->save($notification);

              // Si l'action s'est bien déroulée
              if ($resultat==0){
                  $this->Flash->success(__('Vous avez répondu à la notification.'));

              // Sinon
              }else{
                $this->Flash->success(__('Une erreur s\'est produite.'));
              }

            // Si l'utilisateur a déjà répondu à la notification
            }else{
                $this->Flash->error(__("Vous avez déjà répondu à cette notification."));
            }

        // Si la notification n'existe pas
        } else {
            $this->Flash->error(__("La notification à laquelle vous essayez d'accéder n'existe pas."));
        }
        $this->redirect($this->referer());
    }

    /**
    * Fonction appelée si on accepte une notification de type 'Proprietaire'.
    * L'utilisateur connecté devient le propriétaire du projet de la notification.
    *
    * @param idUtilisateur : id de l'utilisateur connecté
    * @param notification : notification concernée
    * @return 0 si tout est ok, 1 sinon
    *
    * @author Pop Diana, Rossi Djessy
    */
    private function proprietaire($idUtilisateur, $notification){
      $idProjet = $notification->idProjet;
      $resultat = 1;

      // Si la notification a bien un idProjet avec elle
      if ($idProjet!==null){
        $resultat = 0;
        $projets = TableRegistry::getTableLocator()->get('Projet');

        $query = $projets->query();
        $query->update()
        ->set(['idProprietaire' => $idUtilisateur])
        ->where(['idProjet' => $idProjet])->execute();
      }

      return $resultat;
    }

    /**
    * Fonction appelée si on appelle une notification de type 'Invitation'.
    * L'utilisateur connecté est ajouté dans les membres du projet de la notification.
    *
    * @param idUtilisateur : id de l'utilisateur connecté
    * @param notification : notification concernée
    * @return 0 si tout est ok, 1 sinon
    *
    * @author Pop Diana, Rossi Djessy
    */
    private function invitation($idUtilisateur, $notification){
      $idProjet = $notification->idProjet;
      $resultat = 1;

      // Si la notification a bien un idProjet avec elle
      if ($idProjet!==null){
        $resultat = 0;
        $membres = TableRegistry::getTableLocator()->get('Membre');
        $projets = TableRegistry::getTableLocator()->get('Projet');
        $session = $this->request->getSession();

        /* On crée notre nouveau petit membre. */
        $membre = $membres->newEntity();
        $membre->idUtilisateur = $idUtilisateur;
        $membre->idProjet = $idProjet;


        //On récupère le projet concerné pour le nom
        $projet = $projets->find()->where(['idProjet' => $notification->idProjet])->first();

        // On récupère les informations de la tâche pour envoyer une notification
        $contenu = $session->read('Auth.User.pseudo') . " a accepté votre invitation à rejoindre le projet '" . $projet['titre']."'.";

        // Pour chaque membre du projet, on envoie une notification à celui-ci
        $destinataires = array();
        array_push($destinataires, $notification->idExpediteur);

        // On envoie une notification à tous les membres du projet de la tâche supprimer
        envoyerNotification(0, 'Informative', $contenu, $idProjet, null, $notification->idExpediteur, $destinataires);

        /* On le sauvegarde. */
        $estSauvegarde = $membres->save($membre);

        /* On vérifie qu'il n'y a pas eu d'erreurs à la sauvegarde. */
        if (!$estSauvegarde) $resultat = 1;
      }
      return $resultat;
    }



    /**
    * @author Théo Roton
    * @param idNotification : id de la notification
    *
    * Cette fonction permet d'accepter la suppression d'une tâche.
    * Une fois la notification acceptée, on va supprimer la tâche
    * et toutes les notifications qui lui sont liées.
    * On redirige à la fin l'utilisateur sur sa liste de notifications.
    */
    public function accepterSuppressionTache($idNotification){

      // On récupère l'id de l'utilisateur connecté
      $session = $this->request->getSession();
      $idUtilisateur = $session->read('Auth.User.idUtilisateur');

      // On récupère les tables nécessaires à l'opération
      $notifications = TableRegistry::getTableLocator()->get('Notification');
      $vue_notifications = TableRegistry::getTableLocator()->get('VueNotification');
      $taches = TableRegistry::getTableLocator()->get('Tache');

      // On récupère la notificaiton correspondant à la demande de suppression
      $notification = $notifications->find()
      ->where(['idNotification' => $idNotification])
      ->first();
      // On récupère l'id de la tâche à supprimer
      $idTache = $notification->idTache;

      // On récupère les notifications liés à la tâche pour les supprimer
      $notifications_supprs = $notifications->find()->contain('VueNotification')
      ->where(['idTache' => $idTache])
      ->toArray();

      // Pour chaque notification
      foreach ($notifications_supprs as $not) {
        // Pour chaque vue d'une notification
        foreach ($not->notifications as $vue) {
          // On supprime la vue
          $vue_notifications->delete($vue);
        }
        // On supprime la notification
        $notifications->delete($not);
      }

      // On récupère la tâche à supprimer
      $tache = $taches->find()
      ->where(['idTache' => $idTache])
      ->first();

      // On récupère les informations de la tâche pour envoyer une notification
      $contenu = "La tâche ".$tache->titre." a été supprimée.";
      $idProjet = $tache->idProjet;

      // On récupère les membres du projet
      $membres = TableRegistry::getTableLocator()->get('Membre');
      $membres = $membres->find()->contain('Utilisateur')
      ->where(['idProjet' => $idProjet]);

      // Pour chaque membre du projet, on envoie une notification à celui-ci
      $destinataires = array();
      foreach ($membres as $m) {
        $idUtil = $m->un_utilisateur->idUtilisateur;
        array_push($destinataires, $idUtil);
      }

      // On supprime la tâche
      $taches->delete($tache);

      // On envoie une notification à tous les membres du projet de la tâche supprimer
      envoyerNotification(0, 'Informative', $contenu, $idProjet, null, $idUtilisateur, $destinataires);

      $this->Flash->success(__('La tâche a été supprimée'));
      $resultat = 0;
      return $resultat;

      // On redirige l'utilisateur sur la liste de ses notifications
      $this->redirect($this->referer());
    }


    /**
    * @author Théo Roton
    * @param idNotification : id de la notification
    *
    * Cette fonction permet de refuser la suppression d'une tâche.
    * Une fois la notification refusée, on renvoie l'utilisateur
    * sur la liste de ses notifications.
    */
    public function refuserSuppressionTache($idNotification){
      // On récupère l'id de l'utilisateur connecté
      $session = $this->request->getSession();
      $idUtilisateur = $session->read('Auth.User.idUtilisateur');

      // On récupère la table des vues notifications
      $vue_notifications = TableRegistry::getTableLocator()->get('VueNotification');
      // On récupère la notification correspondant à la suppression
      $notification = $vue_notifications->find()
      ->where(['idUtilisateur' => $idUtilisateur])
      ->where(['idNotification' => $idNotification])
      ->first();

      // On change l'état de la vue à réfusé
      $notification->etat = 'Refusé';
      // On indique que la notification a été vue
      $notification->vue = 1;

      $this->Flash->default(__('La tâche n\'a pas été supprimée'));

      // On met à jour la notification
      $vue_notifications->save($notification);

      $resultat = 0;
      return $resultat;

      // On redirige l'utilisateur sur la liste de ses notifications
      $this->redirect($this->referer());

    }



    /**
    * @author Théo Roton
    * @param idNotification : id de la notification à supprimer
    *
    * Cette fonction permet de supprimer une notification de projet identifié
    * par son id. On récupère l'id de l'utilisateur, puis on récupère
    * dans la table des vues notifications la notification correspondante
    * et on la supprime. On renvoie ensuite l'utilisateur sur la liste de
    * ses notifications.
    */
    public function supprimerNotification($idNotification) {

      // On récupère l'id de l'utilisateur connecté
      $session = $this->request->getSession();
      $idUtilisateur = $session->read('Auth.User.idUtilisateur');

      // On récupère la table des vues notifications
      $vue_notifications = TableRegistry::getTableLocator()->get('VueNotification');
      // On récupère la notification correspondant à la suppression
      $notification = $vue_notifications->find()
      ->where(['idUtilisateur' => $idUtilisateur])
      ->where(['idNotification' => $idNotification])
      ->first();

      // On supprime la notification
      $vue_notifications->delete($notification);

      // On compte le nombre de vues liées à la notification correspondate
      $count = $vue_notifications->find()
      ->where(['idNotification' => $idNotification])
      ->count();

      // Si il n'y a plus de vues liées à cette notification, on supprime la notification
      if ($count == 0){
        // On récupère la table des notifications
        $notifications = TableRegistry::getTableLocator()->get('Notification');

        //On récupère la notification
        $notification = $notifications->find()
        ->where(['idNotification' => $idNotification])
        ->first();

        // On supprime la notification
        $notifications->delete($notification);
      }

      // On redirige l'utilisateur sur la liste de ses notifications
      $this->redirect($this->referer());
    }




}
?>
