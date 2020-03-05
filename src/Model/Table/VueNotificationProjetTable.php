<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class VueNotificationProjetTable extends Table {

  public function initialize(array $config){
    $this->setPrimaryKey(array('idUtilisateur','idNotifProjet'));

    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idUtilisateur',
      'propertyName' => 'un_utilisateur'
    ]);

    $this->belongsTo('NotificationProjet', [
      'foreignKey' => 'idNotifProjet',
      'propertyName' => 'une_notification'
    ]);

  }

}

?>
