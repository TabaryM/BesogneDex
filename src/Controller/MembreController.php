<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;


class MembreController extends AppController
{
    /**
    * Affiche les membres d'un projet.
    *
    * Auteur : POP Diana
    */
    public function index(){
      $this->loadComponent('Paginator');
      if (isset($this->request->query['id'])){
        $id = $this->request->query['id'];
      }else{
        die();
        //TODO: affichage erreur (au cas où)
      }

      $session = $this->request->getSession();
      $membres = $this->Paginator->paginate($this->Membre->find()
          ->contain(['Utilisateur'])
          ->where(['idProjet' => $this->request->query['id']]));
      $this->set(compact('membres'));
    }

}

?>
