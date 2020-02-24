<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ProjetTable extends Table{
  public function initialize(array $config){
    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idProprietaire',
      'propertyName' => 'un_utilisateur'
    ]);

    $this->hasMany('Membre', [
      'bindingKey' => 'idProjet',
      'foreignKey' => 'idProjet',
      'propertyName' => 'un_membre'
    ]);

    $this->hasMany('tache')
      ->setForeignKey('idProjet')
      ->setBindingKey('idProjet');

  }
}


 ?>
