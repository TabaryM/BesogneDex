<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ProjetTable extends Table{

  public function initialize(array $config){
    $this->setPrimaryKey('idProjet');
    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idProprietaire',
      'propertyName' => 'un_utilisateur'
    ]);

    $this->hasMany('Membre', [
      'bindingKey' => 'idProjet',
      'foreignKey' => 'idProjet',
      'propertyName' => 'un_membre'
    ]);

    $this->hasMany('Tache')
      ->setForeignKey('idProjet')
      ->setBindingKey('idProjet');


    $this->hasMany('Tache', [
        'className' => 'Tache',
        'bindingKey' => 'idProjet',
        'foreignKey' => 'idProjet',
        'dependent' => true,
        'cascadeCallbacks' => true
    ]);


      $this->hasMany('Notification_projet', [
          'className' => 'Notification_projet',
          'foreignKey'=> 'idProjet',
          'dependent' => true,
          'cascadeCallbacks' =>true
      ]);

  }
}


 ?>
