<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;


class NotificationController extends AppController
{

  public function index(){
    $this->loadComponent('Paginator');
    $session = $this->request->getSession();
    $table_notifs_taches = TableRegistry::getTableLocator()->get('Vue_notification_projet');
    $notificationsProjet = $this->Paginator->paginate($table_notifs_taches->find());

    $this->set(compact('notificationsProjet'));
  }


}
?>
