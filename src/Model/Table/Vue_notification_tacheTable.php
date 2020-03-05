<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class Vue_notification_tacheTable extends Table {

  public function initialize(array $config){
    $this->setPrimaryKey(array('idUtilisateur','idNotifTache'));

    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idUtilisateur',
      'propertyName' => 'un_utilisateur'
    ]);

    $this->belongsTo('Notification_tache', [
      'foreignKey' => 'idNotifTache',
      'propertyName' => 'une_notification'
    ]);

  }

}

?>
