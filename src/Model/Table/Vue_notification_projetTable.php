<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class Vue_notification_projetTable extends Table {

  public function initialize(array $config){
    $this->setPrimaryKey(array('idUtilisateur','idNotifProjet'));

    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idUtilisateur',
      'propertyName' => 'un_utilisateur'
    ]);

    $this->belongsTo('Notification_projet', [
      'foreignKey' => 'idNotifProjet',
      'propertyName' => 'une_notification'
    ]);

  }

}

?>
