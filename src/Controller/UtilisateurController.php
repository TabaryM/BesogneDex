<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

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
  * Permet à l'utilisateur de se déconnecter.
  * La page qui appelle cette fonction est : Template/Element/header.ctp
  *
  * @author POP Diana
  */
  public function logout(){
    return $this->redirect($this->Auth->logout());
  }

  public function profil(){
    return null;
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
}

?>
