<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
* Auteur : Diana
*/
class UtilisateurController extends AppController
{

  public function initialize()
  {
      parent::initialize();
  }

  /**
  * Pris de la doc officielle :
  * Permet les utilisateurs de s'inscrire et de se déconnecter.
  * La doc demande à ne pas ajouter 'login' dans la liste pour ne pas causer de problèmes avec le fonctionnement normal de AuthComponent.
  *
  * Auteur : POP Diana
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
  * Auteur : POP Diana
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
  * Auteur : POP Diana
  */
  public function add(){
      $utilisateur = $this->Utilisateur->newEntity();
      if ($this->request->is('post')) {
          $utilisateur = $this->Utilisateur->patchEntity($utilisateur, $this->request->getData());
          if ($this->Utilisateur->save($utilisateur)) {
              $this->Flash->success(__('Votre compte est bien enregistré.'));
              return $this->redirect(['controller' => 'pages', 'action' => 'display','home']);
          }

          if($utilisateur->errors()){
               $error_msg = [];
               foreach( $utilisateur->errors() as $errors){
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
          return $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));
      }
        $this->set('utilisateur', $utilisateur);
  }

  /**
  * Fonction pour auto-complétion de Membre/index
  *
  * Auteur : POP Diana (c'est un c/c de ce site : http://www.naidim.org/cakephp-3-tutorial-18-autocomplete)
  */
    function complete(){
      if ($this->requrest->is('ajax')) {
          $this->autoRender = false;
          $name = $this->request->query['term'];
          $results = $this->Utilisateur->find('all', [
              'conditions' => [
                  'pseudo LIKE' => $name.'%',
              ]
          ]);
          $resultsArr = [];
          foreach ($results as $result) {
               $resultsArr[] =['label' => $result['pseudo'], 'value' => $result['id']];
              debug($result);
              die();
          }
          echo $this->response->body(json_encode($resultsArr));
      }
}

  /**
  * Permet à l'utilisateur de se déconnecter.
  * La page qui appelle cette fonction est : Template/Element/header.ctp
  *
  * Auteur : POP Diana
  */
  public function logout(){
    return $this->redirect($this->Auth->logout());
  }

  public function edit(){
    return null;
  }

}

?>
