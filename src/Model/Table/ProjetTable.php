<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class ProjetTable extends Table{
  public function initialize(array $config){
    $this->hasMany('tache')
      ->setForeignKey('idProjet')
      ->setBindingKey('idProjet');
    ;
  }
}


 ?>
