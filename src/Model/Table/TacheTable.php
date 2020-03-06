<?php
namespace App\Model\Table;

use Cake\Validation\Validator;
use Cake\ORM\Table;

class TacheTable extends Table{
  /**
  * Initialisation base de données côté Table Tache
  * @author Thibault CHONÉ
  * @param $config : CakePhp gère ça
  */
  public function initialize(array $config){
    $this->setPrimaryKey('idTache');

    $this->belongsTo('Utilisateur', [
      'foreignKey' => 'idResponsable',
      'propertyName' => 'responsable'
    ]);

    $this->belongsTo('Projet', [
      'foreignKey' => 'idProjet',
      'propertyName' => 'leProjet'
    ]);

      $this->hasMany('NotificationTache', [
          'className' => 'NotificationTache',
          'foreignKey'=> 'idProjet',
          'dependent' => true,
          'cascadeCallbbacks' =>true
      ]);

  }

/**
 * Vérifications du formulaire de création/modif de tâche avec messsages d'erreurs.
 * @author Thibault CHONÉ
 * @param  Validator $validator Le validator a modifier
 * @return Validator            Le validator modifié
 */
public function validationDefault(Validator $validator){
  return $validator
  //->requirePresence('titre', 'true')
  ->notEmptyString('titre', 'Le titre ne peut pas être vide.',  true)
  ->lengthBetween('titre', [0, 128], 'Le titre de la tâche est trop long (max 128 caractères).')
  // Vérification de l'unicité du titre
  ->add('titre' , array(
      'unique' => array(
        'rule' => function($value, $context){
          $rech_titre = $context['data']['titre'];
          $idProjet = $context['data']['idProjet'];
          $count =  $this->find()->where(['titre' => $rech_titre, 'idProjet' => $idProjet])->count();
          return $count == 0;
          },
          'message' => 'Ce titre est déjà utilisé.'
        )

      )
    )// add titre
    ->allowEmptyString('description')
    ->lengthBetween('description', [0, 512], 'La description de la tâche est trop longue (max 512 caractères).')
    //->add('description', 'string')
    ;

  }
}


 ?>
