<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class VueNotificationTable extends Table {

  public function initialize(array $config){
    $this->setPrimaryKey(array('idUtilisateur','idNotification'));

    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idUtilisateur',
      'propertyName' => 'un_utilisateur'
    ]);

    $this->belongsTo('Notification', [
      'foreignKey' => 'idNotification',
      'propertyName' => 'une_notification'
    ]);
  }

}

?>
