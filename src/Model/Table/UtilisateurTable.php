<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class UtilisateurTable extends Table{

    /**
     * Constructeur permettant de définir les relations entre les entités
     * (utile notamment pour la suppression en cascade ou les requêtes complexes)
     * @param array $config
     * @author : PALMIERI Adrien
     */
  public function initialize(array $config){

      $this->setPrimaryKey('idUtilisateur');

      $this->hasMany('Projet', [
          'className' => 'Projet',
          'foreignKey' => 'idProprietaire',
          'dependent' => true,
          'cascadeCallbacks' => true,
      ]);

      $this->hasMany('Tache', [
          'className' => 'Tache',
          'foreignKey' => 'idResponsable'
      ]);

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
          // Toutes les conditions supplémentaires relatives à l'adresse mail
          ->add(  'email' , array(
                  'email'   => array(
                    'rule'    => array('email'),
                    'message' => 'Veuillez saisir une adresse électronique valide.'
                  ),
                'unique' => array(
                    'rule' => function($value, $context){
                        $rech_email = $context['data']['email'];
                        $count =  $this->find()->where(['email' => $rech_email])->count();
                        return $count == 0;
                      },
                    'message' => 'Cette adresse email est déjà utilisée.'
            )

        )// fin array 'email'
      )// fin add de email
      ->notEmpty('pseudo', 'Un pseudo est nécessaire.')

      ->add('pseudo', array(
              'longueur' => array(
                'rule' => ['lengthbetween', 3, 15],
                'message' => 'Le pseudo doit contenir entre 3 et 15 caractères.'
              ),
                'unique' => array(
                  'rule' => function($value, $context){
                        $rech_pseudo = $context['data']['pseudo'];
                        $count = $this->find()->where(['pseudo' => $rech_pseudo])->count();
                        return $count == 0;
                    },
                  'message' => 'Ce pseudo est déjà utilisé.'
                ),
              'validite' => array(
                'rule' => function($value, $context){
                    $val_pseudo = $context['data']['pseudo'];
                    return (bool)preg_match("/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/", $val_pseudo);
                  },
                'message'=> 'Le pseudo est incorrect.'
              )
        )// fin array 'pseudo'
      ) // fin add de pseudo
        ->notEmpty('mdp', 'Un mot de passe est nécessaire.')
        ->notEmpty('mdpp', 'Une confirmation du mot de passe est nécessaire.')
        ->add('mdp', array(
            'longueurMin' => array(
              'rule' => function($value, $context){
                    $val_mdp = $context['data']['mdp'];
                    return (bool)preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $val_mdp);
                },
              'message' => 'Le mot de passe doit correspondre aux critères.'
            ),
              'identiques' => array(
                'rule' => function($value, $context){
                    $val_mdp = $context['data']['mdp'];
                    $val_mdpp = $context['data']['mdpp'];
                    return $val_mdp === $val_mdpp;
                  },
                'message' => 'Les mots de passe doivent correspondre.'
            )
        )// fin array 'mdp'
    )// fin add de mdp
      ->add('prenom', array(
          'validite' => array (
            'rule' => function($value, $context){
                  $val_prenom = $context['data']['prenom'];
                  return (bool)preg_match("/[A-Za-z]*[-]?[A-Za-z]*/", $val_prenom);
              },
            'message' => 'Le prénom est incorrect.',
            'allowEmpty' => true
          )
      )// fin array 'prenom'
    )//fin add de prenom
      ->add('nom', array(
          'validite' => array(
            'rule' => function($value, $context){
                $val_nom = $context['data']['nom'];
                return (bool)preg_match("/[A-Za-z]*[-]?[A-Za-z]*/", $val_nom);
              },
            'message' => 'Le nom est incorrect.',
            'allowEmpty' => true
          )

      )// fin array de 'nom'
    )//fin add de nom
    ->allowEmptyString('prenom')
    ->allowEmptyString('nom')
    ;// point-virgule vital

  }

}
?>
