<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class NotificationTacheTable extends Table {

  public function initialize(array $config){
    $this->setPrimaryKey('idNotificationTache');

    $this->belongsTo('Tache', [
      'foreignKey' => 'idTache',
      'propertyName' => 'tache_liee'
    ]);

    $this->hasMany('VueNotificationTache', [
      'bindingKey' => 'idNotifTache',
      'foreignKey' => 'idNotificationTache',
      'propertyName' => 'une_notification'
    ]);

  }

}

?>
