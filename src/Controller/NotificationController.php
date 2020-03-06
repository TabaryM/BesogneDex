<?php
namespace App\Controller;

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
      $this->Flash->error(_('Vous devez être connecté pour accéder à vos notifications.'));
      $this->redirect($this->referer());
    }
  }

  /**
  * Les notifications non-vues et non à valider deviennent vues lorsque l'utilisateur va voir ses notifications.
  * La fonction est appelée par index() de ce controller.
  * Un simple update ne convient pas car il est nécessaire d'aller chercher l'attribut "a_valider" dans une autre table (tables NotificationProjet et NotificationTache).
  * @param tableNotificationsProjet TableRegistry de VueNotificationProjet
  * @param tableNotificationsTache TableRegistry de VueNotificationTache
  * @param idUtilisateur id de l'utilisateur connecté
  * @return / Redirection : /
  * @author POP Diana
  */
  private function updateNotificationsVues($tableNotificationsProjet, $tableNotificationsTache, $idUtilisateur){
    // On commence par les notifications de projet.
    $notificationsProjetVues = $tableNotificationsProjet->find()->contain('NotificationProjet')->where(['idUtilisateur'=>$idUtilisateur, 'a_valider'=>0])->toArray();
    if ($notificationsProjetVues){
      foreach($notificationsProjetVues as $notification){
        $notification->vue = 1;
        $tableNotificationsProjet->save($notification);
      }

      // Maintenant, on fait les notifications de tâche.
      $notificationsTacheVues = $tableNotificationsTache->find()->contain('NotificationTache')->where(['idUtilisateur'=>$idUtilisateur, 'a_valider'=>0])->toArray();
      if ($notificationsTacheVues){
        foreach($notificationsTacheVues as $notification){
          $notification->vue = 1;
          $tableNotificationsTache->save($notification);
        }

      }
    }
  }

  /**
   * Affiche les notifications de l'utilisateur et met à jour la BDD pour dire que les notifications ont été vues.
   * La fonction est appelée au clic sur la cloche de notification depuis n'importe quelle page où l'utilisateur est connecté.
   * Les notifications à valider sont affichées en premier, puis sont par date décroissante.
   * Les notifications non vues par l'utilisateur (= sont apparues depuis la dernière fois qu'il a cliqué sur la cloche) sont affichées en gras.
   * @return Redirection : si l'utilisateur n'est pas connecté, renvoie à la page d'inscription.
   * @author POP Diana, SOUSA RIBIERO Pedro
   */
  public function index(){
    $idUtilisateur= $this->autorisation();

    // Initialisation des tables
    $tableNotificationsProjet = TableRegistry::getTableLocator()->get('VueNotificationProjet');
    $tableNotificationsTache = TableRegistry::getTableLocator()->get('VueNotificationTache');

    // Récupération des notifications de projet
    $notificationsProjet = $tableNotificationsProjet->find()->contain(['NotificationProjet'])->where(['idUtilisateur' => $idUtilisateur])->toArray();
    $notificationsTache = $tableNotificationsTache->find()->contain(['NotificationTache'])->where(['idUtilisateur' => $idUtilisateur])->toArray();

    // On merge en une seule array les résultats des deux requêtes.
    $notifs = array_merge($notificationsProjet, $notificationsTache);

    // On trie l'array résultante. Le tri est déjà sur la date, puis sur si la notification est à valider.
    $notifs = Hash::sort($notifs, '{n}.une_notification.Date','asc');
    $notifs = Hash::sort($notifs, '{n}.une_notification.a_valider', 'desc');

    // On met à jour les notifications vues seulement après leur affichage.
    $this->updateNotificationsVues($tableNotificationsProjet, $tableNotificationsTache, $idUtilisateur);

    // Donne aux ctp les variables nécessaires
    $this->set(compact('notifs'));

  }

}
?>
