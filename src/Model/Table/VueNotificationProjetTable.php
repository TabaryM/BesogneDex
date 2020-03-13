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

    $this->hasMany('NotificationProjet', [
      'bindingKey' => 'idNotifProjet',
      'foreignKey' => 'idNotificationProjet',
      'propertyName' => 'une_notification_utilisateur',
        'dependent' => true,
        'cascadeCallbacks' => true
    ]);

  }

}

?>
