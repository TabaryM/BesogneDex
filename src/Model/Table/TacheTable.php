<?php
namespace App\Model\Table;

use Cake\Validation\Validator;
use Cake\ORM\Table;

class TacheTable extends Table{
  /**
  * Initialisation base de données côté Table Tache
  * @author : Thibault CHONÉ
  * @param $config : aucune idée
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
  }


  /**
* Vérifications du formulaire de création/modif de tâche avec messsages d'erreurs.
* @author : Thibault CHONÉ
*/
public function validationDefault(Validator $validator){
  return $validator
  ->requirePresence('titre')
  ->notEmptyString('titre', 'Un titre est nécessaire')
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
    //->add('description', 'string')
    ;

  }
}


 ?>
