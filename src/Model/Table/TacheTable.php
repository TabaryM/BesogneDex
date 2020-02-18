<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class TacheTable extends Table{
  /**
  * Initialisation base de données côté Table Tache
  *
  * @author : Thibault CHONÉ
  */
  public function initialize(array $config){
    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idResponsable',
      'propertyName' => 'responsable'
    ]);
    $this->belongsTo('Projet', [
      'foreignKey' => 'idProjet',
      'propertyName' => 'leProjet'
    ]);
  }
}


 ?>
