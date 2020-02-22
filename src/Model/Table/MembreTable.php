<?php
namespace App\Model\Table;

use Cake\ORM\Table;


class MembreTable extends Table{

/**
* Permet de lier les tables Projet et Utilisateur pour les queries.
*
* Auteur : POP Diana
*/
  public function initialize(array $config){
    /*
    $this->hasOne('Utilisateur')
         ->setForeignKey('idUtilisateur')
         ->setBindingKey('idUtilisateur')
         ->setProperty('un_utilisateur');
    $this->hasMany('Projet')
      ->setForeignKey('idProjet')
      ->setBindingKey('idProjet');
      */
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
