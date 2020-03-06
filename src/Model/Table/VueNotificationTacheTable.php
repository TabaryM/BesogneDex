<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class VueNotificationTacheTable extends Table {

  public function initialize(array $config){
    $this->setPrimaryKey(array('idUtilisateur','idNotifTache'));

    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idUtilisateur',
      'propertyName' => 'un_utilisateur'
    ]);

    $this->belongsTo('NotificationTache', [
      'foreignKey' => 'idNotifTache',
      'propertyName' => 'une_notification'
    ]);

  }

}

?>
