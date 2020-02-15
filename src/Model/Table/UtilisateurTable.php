<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UtilisateurTable extends Table{

  public function initialize(array $config){
    ;
  }

/**
* Vérifications du formulaire d'inscription avec messsages d'erreurs.
*
* Auteur : POP Diana
*/
  public function validationDefault(Validator $validator)
  {
    return $validator
    ->notEmpty('email', 'Une adresse email est nécessaire.')
    ->notEmpty('mdp', 'Un mot de passe est nécessaire.')
    ->notEmpty('pseudo', 'Un pseudo est nécessaire.');
    }
}

 ?>
