<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TacheTable extends Table{
  public function initialize(array $config){
    $this->BelongsTo('Utilisateur', [
      'foreignKey' => 'idResponsable',
      'propertyName' => 'responsable'
    ]);
    $this->BelongsTo('Projet', [
      'foreignKey' => 'idProjet',
      'propertyName' => 'leProjet'
    ]);
  }
}


 ?>
