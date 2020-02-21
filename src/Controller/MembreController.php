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
    public function index($id){
      $this->loadComponent('Paginator');
      $session = $this->request->getSession();
      $membres = $this->Paginator->paginate($this->Membre->find()
          ->contain(['Utilisateur'])
          ->where(['idProjet' => $id]));
      $this->set(compact('membres', 'id'));
    }


    /**
    * TODO: sprint 5, envoi notif
    * @author POP Diana
    */
    public function add($id){
      if ($this->request->is('post')){

        $membre = $this->Membre->newEntity();

        $membre->idProjet= $id;
        $membre->idUtilisateur= $this->request->getData()['recherche_utilisateurs'];

        if ($this->Membre->save($membre)) {
          $this->Flash->success(__('Le membre a été ajouté à la liste.'));

          return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id]);
        }
        $this->Flash->error(__('Impossible d\'ajouter ce membre.'));
      }
    }

}

?>
