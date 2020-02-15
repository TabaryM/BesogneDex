<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
* Auteur : Diana
*/
class UtilisateurController extends AppController
{

  public function beforeFilter(Event $event)
  {
      parent::beforeFilter($event);
      // Allow users to register and logout.
      // You should not add the "login" action to allow list. Doing so would
      // cause problems with normal functioning of AuthComponent.
      $this->Auth->allow(['login','add', 'logout']);
  }


  public function login(){
    if ($this->request->is('post')){
      $utilisateur = $this->Auth->identify();
      if ($utilisateur){
        $this->Auth->setUser($utilisateur);
        $this->Flash->success(__('Vous êtes connecté !'));
        return $this->redirect($this->Auth->redirectUrl());
      }
      $this->Flash->error(__('E-mail ou mot de passe incorrects'));
    }

  }

  public function add(){
      $utilisateur = $this->Utilisateur->newEntity();
      if ($this->request->is('post')) {
          $utilisateur = $this->Utilisateur->patchEntity($utilisateur, $this->request->getData());
          if ($this->Utilisateur->save($utilisateur)) {
              $this->Flash->success(__('The user has been saved.'));
              return $this->redirect(['action' => 'login']);
          }
          $this->Flash->error(__('Unable to add the user.'));
      }
        $this->set('utilisateur', $utilisateur);
  }

  public function logout(){
    return $this->redirect($this->Auth->logout());
  }

}

?>
