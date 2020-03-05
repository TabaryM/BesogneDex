<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class NotificationProjetTable extends Table {

  public function initialize(array $config){
    $this->setPrimaryKey('idNotificationProjet');

    $this->belongsTo('Projet', [
      'foreignKey' => 'idProjet',
      'propertyName' => 'projet_liee'
    ]);

    $this->hasMany('VueNotificationProjet', [
      'bindingKey' => 'idNotifProjet',
      'foreignKey' => 'idNotificationProjet',
      'propertyName' => 'une_notification'
    ]);

  }

}

?>
