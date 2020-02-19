<?php
namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

class Utilisateur extends Entity
{
    protected $_accessible = ['*' => true ];

    /**
    * Permet le hashage du mot de passe.
    * Le nom de la fonction est à l'origine _setPassword mais a été modifiée NÉCESSAIREMENT en _setMdp car 'mdp' est le champ du mdp dans notre BDD.
    *
    * Auteur : POP Diana
    */
    protected function _setMdp($value){
      return (new DefaultPasswordHasher)->hash($value);
    }


     /**
     * Constructeur permettant de définir les relations entre les entités
     * (utile notamment pour la suppression en cascade ou les requêtes complexes)
     * @param array $config
     * @author : PALMIERI Adrien
     */
    public function initialize(array $config) {

        $this->hasMany('Projet', [
                       'className' => 'Projet',
                       'dependent' => true,
                       'cascadeCallbacks' => true,
        ]);
        $this->hasMany('Tache', [
                        'className' => 'Tache',
                        'dependent' => true,
                        'cascadeCallbacks' => true
        ]);
    }


}
