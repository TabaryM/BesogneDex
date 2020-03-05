<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;


class NotificationController extends AppController
{

  public function index(){
    $session = $this->request->getSession();
    $notifs = null;
    if ($session->check('Auth.User.idUtilisateur')) {
      $userID = $session->read('Auth.User.idUtilisateur');
      $table_notifs_taches = TableRegistry::getTableLocator()->get('VueNotificationProjet');
      $notifs = $table_notifs_taches->find()->contain(['NotificationProjet'])->where(['idUtilisateur' => $userID])->toArray();
      debug($notifs);
      die();
      $this->set(compact('notifs'));
    } else {
      $this->Flash->error(_('Connectez vous pour accéder a vos notifications'));
      $this->redirect($this->referer());
    }
  }


}
?>
