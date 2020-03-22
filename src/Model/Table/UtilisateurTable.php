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
          'cascadeCallbacks' => true
      ]);

      $this->hasMany('Tache', [
          'className' => 'Tache',
          'foreignKey' => 'idResponsable'
      ]);

      $this->hasMany('VueNotification', [
          'className' => 'VueNotification',
          'foreignKey'=> 'idUtilisateur',
          'dependent' => true,
          'cascadeCallbbacks' =>true
      ]);

  }

/**
* Vérifications du formulaire d'inscription avec messsages d'erreurs.
* @author POP Diana
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
                        $rechercheEmail = $context['data']['email'];
                        $count =  $this->find()->where(['email' => $rechercheEmail])->count();
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
                        $recherchePseudo = $context['data']['pseudo'];
                        $count = $this->find()->where(['pseudo' => $recherchePseudo])->count();
                        return $count == 0;
                    },
                  'message' => 'Ce pseudo est déjà utilisé.'
                ),
              'validite' => array(
                'rule' => function($value, $context){
                    $pseudo = $context['data']['pseudo'];
                    return (bool)preg_match("/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/", $pseudo);
                  },
                'message'=> 'Le pseudo est incorrect.'
              )
        )// fin array 'pseudo'
      ) // fin add de pseudo
        ->notEmpty('mdp', 'Un mot de passe est nécessaire.')
        ->notEmpty('mdpConfirm', 'Une confirmation du mot de passe est nécessaire.')
        ->add('mdp', array(
            'longueurMin' => array(
              'rule' => function($value, $context){
                    $mdp = $context['data']['mdp'];
                    return (bool)preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $mdp);
                },
              'message' => 'Le mot de passe doit correspondre aux critères.'
            ),
              'identiques' => array(
                'rule' => function($value, $context){
                    $mdp = $context['data']['mdp'];
                    $mdpConfirm = $context['data']['mdpConfirm'];
                    return $mdp === $mdpConfirm;
                  },
                'message' => 'Les mots de passe doivent correspondre.'
            )
        )// fin array 'mdp'
    )// fin add de mdp
      ->add('prenom', array(
          'validite' => array (
            'rule' => function($value, $context){
                  $prenom = $context['data']['prenom'];
                  return (bool)preg_match("/[A-Za-z]*[-]?[A-Za-z]*/", $prenom);
              },
            'message' => 'Le prénom est incorrect.',
            'allowEmpty' => true
          )
      )// fin array 'prenom'
    )//fin add de prenom
      ->add('nom', array(
          'validite' => array(
            'rule' => function($value, $context){
                $nom = $context['data']['nom'];
                return (bool)preg_match("/[A-Za-z]*[-]?[A-Za-z]*/", $nom);
              },
            'message' => 'Le nom est incorrect.',
            'allowEmpty' => true
          )

      )// fin array de 'nom'
    )//fin add de nom
    ->allowEmptyString('prenom')
    ->allowEmptyString('nom')
    ;

  }

}
?>
