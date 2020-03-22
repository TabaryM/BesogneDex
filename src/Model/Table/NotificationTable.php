<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class NotificationTable extends Table {

  public function initialize(array $config){
    $this->setPrimaryKey('idNotification');

    $this->belongsTo('Projet', [
      'foreignKey' => 'idProjet',
      'propertyName' => 'projet_liee'
    ]);

    $this->belongsTo('Tache', [
      'foreignKey' => 'idTache',
      'propertyName' => 'tache_liee'
    ]);

    $this->hasMany('VueNotification', [
      'bindingKey' => 'idNotification',
      'foreignKey' => 'idNotification',
      'propertyName' => 'notifications'
    ]);
  }

}

?>
