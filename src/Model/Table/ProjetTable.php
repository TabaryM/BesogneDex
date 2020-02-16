<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ProjetTable extends Table{
  public function initialize(array $config){
    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idProprietaire',
      'propertyName' => 'un_utilisateur'
    ]);
    $this->hasMany('tache')
      ->setForeignKey('idProjet')
      ->setBindingKey('idProjet');
  }
}


 ?>
