<?php
// src/Model/Entity/Article.php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Tache extends Entity
{
    protected $_accessible = [
        '*' => true,
    ];

        /**
         * Constructeur permettant de définir les relations entre les entités
         * (utile notamment pour la suppression en cascade ou les requêtes complexes)
         * @param array $config
         * @author : PALMIERI Adrien
         */
        public function initialize(array $config)
        {
            $this->belongsTo('Utilisateur');
            $this->belongsTo('Projet');
        }

}

?>
