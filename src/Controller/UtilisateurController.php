<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
* @author Diana
*/
class UtilisateurController extends AppController
{

  public function initialize()
  {
      parent::initialize();
  }

  /**
  * Permet d'afficher les erreurs
  *
  * @author Diana POP, (Thibault CHONÉ)
  * @param $ArrayError : Liste des erreurs à afficher (il est possible que cette variable contiennent également des tableaux d'erreurs)
  */
  private function affichage_erreurs($ArrayError){
    if($ArrayError){
      $error_msg = [];
      foreach($ArrayError as $errors){
        if(is_array($errors)){
          foreach($errors as $error){
            $error_msg[]    =   $error;
          }
        }else{
          $error_msg[]    =   $errors;
        }
      }

      if(!empty($error_msg)){
        $this->Flash->error(
          __("Veuillez modifier ce(s) champs : ".implode("\n \r", $error_msg))
        );
      }
    }
  }

  public function index(){
      return $this->redirect(['action'=> 'profil']);
  }

  /**
  * Pris de la doc officielle :
  * Permet les utilisateurs de s'inscrire et de se déconnecter.
  * La doc demande à ne pas ajouter 'login' dans la liste pour ne pas causer de problèmes avec le fonctionnement normal de AuthComponent.
  *
  * @author POP Diana
  */
  public function beforeFilter(Event $event)
  {
      parent::beforeFilter($event);
      $this->Auth->allow(['add', 'logout']);
  }

  /**
  * Permet à l'utilisateur de se connecter.
  * Les pages qui appellent cette fonction sont : Template/Element/header.ctp et Template/Utilisateur/login.ctp.
  *
  * @author POP Diana
  */
  public function login(){
    if ($this->request->is('post')){
      $utilisateur = $this->Auth->identify();
      if ($utilisateur){
        $this->Auth->setUser($utilisateur);
        $this->Flash->success(__('Vous êtes connecté !'));
        return $this->redirect(['controller' => 'Accueil', 'action' => 'index']);
      }else{
        $this->Flash->error(__('E-mail ou mot de passe incorrects'));
        return $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));
    }

  }
}

  /**
  * Permet à l'utilisateur de s'inscrire.
  * La page qui appelle cette fonction est : Template/Pages/home.ctp.
  *
  * @author POP Diana
  */
  public function add(){
      $utilisateur = $this->Utilisateur->newEntity();
      if ($this->request->is('post')) {
          $utilisateur = $this->Utilisateur->patchEntity($utilisateur, $this->request->getData());
          if ($this->Utilisateur->save($utilisateur)) {
              $this->Flash->success(__('Votre compte est bien enregistré.'));
              return $this->redirect(['controller' => 'pages', 'action' => 'display','home']);
          }

          $this->affichage_erreurs($utilisateur->errors());

          return $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));
      }
      $this->set('utilisateur', $utilisateur);
  }

  /**
  * Fonction pour auto-complétion de Membre/index
  *
  * Auteur : POP Diana (c'est un presque c/c de ce site : http://www.naidim.org/cakephp-3-tutorial-18-autocomplete)
  */
    function complete(){
          $this->autoRender = false;
          $name = $this->request->query['term'];
          $results = $this->Utilisateur->find('all', [
              'conditions' => [
                  'pseudo LIKE' => $name.'%',
              ]
          ]);
          $resultsArr = [];
          foreach ($results as $result) {
               $resultsArr[] =['label' => $result['pseudo'], 'value' => $result['pseudo']];

          }
          echo json_encode($resultsArr);
}

  /**
  * Permet à l'utilisateur de se déconnecter.
  * La page qui appelle cette fonction est : Template/Element/Utilisateur/logout_confirmation.ctp
  *
  * @author POP Diana
  */
  public function logout(){
    return $this->redirect($this->Auth->logout());
  }

  /**
  * Utilisée dans la page : Template/Element/header.ctp
  *
  * @author MARISSENS Valérie
  */
  public function logoutConfirmation(){
    return null;
  }

    /**
     * Affiche le profil utilisateur
     *
     * @author Mathieu TABARY
     */
  public function profil(){
      // Récupère le cookie de session
      $session = $this->request->getSession();
      // Récupère la table utilisateur
      $utilisateurs = TableRegistry::getTableLocator()->get('utilisateur');

      // Récupère les données de l'utilisateur connecté
      $utilisateur = $utilisateurs->find()
          ->where(['idUtilisateur' => $session->read('Auth.User.idUtilisateur')])
          ->first();

      // On enregistre les données de l'utilisateur connecté dans une varible réutilisable dans le fichier .ctp
      $this->set(compact('utilisateur'));
  }

  /**
  * Enregistre les nouvelles informations dans la base de données.
  *
  * @author Thibault CHONÉ
  */
  public function edit(){
    $session = $this->request->getSession();
    $data = $this->request->getData();
    $data = array_filter($data, function($value) { return !is_null($value) && $value !== '' && !empty($value); }); //On supprime les éléments vide
    if(!empty($data)){
      $utilisateur = $this->Utilisateur->get($session->read('Auth.User.idUtilisateur'));
      $data2 = $this->Utilisateur->patchEntity($utilisateur, $data);

      if($this->Utilisateur->save($data2)){
        $this->Flash->success(__('Votre compte a été édité.'));
      }

      $this->affichage_erreurs($utilisateur->errors());
    }
    $utilisateur = $this->Utilisateur->find()
      ->where(['idUtilisateur' => $session->read('Auth.User.idUtilisateur')])
      ->first();
    $this->set(compact('utilisateur'));
  }


  public function deleteConfirmation() {


  }

    /** Supprime le compte de l'utilisateur ainsi que les données associées
     *
     * @author PALMIERI Adrien
     */
    // IMPORTANT : Lorsque les notifications seront ajoutées, il faudra ajouter la suppression des notifications associées.
    // TODO : Verifier que la suppression des projets / taches associées fonctionne : acces interface PHPMYADMIN timeout... demander Pedro.
    public function deleteAccount() {
     $currentUserId = $this->request->getSession()->read('Auth.User.idUtilisateur');
     $utilisateur = $this->Utilisateur->get($currentUserId);

     if(empty($utilisateur)) {
         $this->Flash->error(__('Impossible de supprimer votre compte utilisateur : vérifiez qu\'il existe et que vous êtes bien connecté.'));
     } else {
         $success = $this->Utilisateur->delete($utilisateur);
         if($success) {
             $this->Auth->logout();
             $this->Flash->success(__('Vous avez supprimé votre compte avec succès'));
             $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));
         } else {
             $this->Flash->error(__('Impossible de supprimer votre compte utilisateur  ou les projets/tâches associé(e)s à celui-ci'));

         }
     }
  }

}

?>
