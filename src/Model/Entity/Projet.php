<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Projet extends Entity
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
        $this->hasMany('Tache', [
            'className' => 'Tache',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);
    }
}

?>
