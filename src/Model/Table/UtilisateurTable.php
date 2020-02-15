<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UtilisateurTable extends Table{

  public function initialize(array $config){
    ;
  }

  public function validationDefault(Validator $validator)
  {
    return $validator
    ->notEmpty('email', 'A username is required')
    ->notEmpty('mdp', 'A password is required')
    ->notEmpty('pseudo', 'Un pseudo est nécessaire');
    }
}

 ?>
