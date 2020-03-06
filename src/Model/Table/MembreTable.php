<?php
namespace App\Model\Table;

use Cake\ORM\Table;


class MembreTable extends Table{

/**
* Permet de lier les tables Projet et Utilisateur pour les queries.
* Auteur : POP Diana
*/
  public function initialize(array $config){
      $this->setPrimaryKey(array('idUtilisateur', 'idProjet'));
      $this->belongsTo('Utilisateur', [
        'foreignKey' => 'idUtilisateur',
        'propertyName' => 'un_utilisateur'
      ]);

      $this->belongsTo('Projet', [
        'foreignKey' => 'idProjet',
        'propertyName' => 'un_projet'
      ]);

  }
}


 ?>
