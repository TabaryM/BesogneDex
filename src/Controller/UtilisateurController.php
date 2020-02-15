<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
* Auteur : Diana
*/
class UtilisateurController extends AppController
{

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
        return $this->redirect($this->Auth->redirectUrl());
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
              $this->Flash->success(__('Votre compte est bien enregistré..'));
              return $this->redirect(['action' => 'login']);
          }
          $this->Flash->error(__('Impossible de créer votre compte.'));
          return $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));
      }
        $this->set('utilisateur', $utilisateur);
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

}

?>
