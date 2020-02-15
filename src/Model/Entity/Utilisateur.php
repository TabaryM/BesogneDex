<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class Utilisateur extends Entity
{
    protected $_accessible = ['*' => true ];

    protected function _setMdp($value){
      return (new DefaultPasswordHasher)->hash($value);
    }


}
