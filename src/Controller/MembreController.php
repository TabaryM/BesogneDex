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
      $estProprietaire = false;
      $this->loadComponent('Paginator');

      $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
        ->get('Projet')->find()
        ->where(['idProjet' => $id])
        ->first();

      $session = $this->request->getSession();
      if ($session->check('Auth.User.idUtilisateur')) {
        $user = $session->read('Auth.User.idUtilisateur');
        if($projetTab->idProprietaire == $user){
          $estProprietaire = true;
      }else{
          $this->Flash->error(__('Ce projet n\'existe pas ou vous n\'y avez pas accès.'));
          $this->redirect(['controller'=>'Accueil', 'action'=>'index']);
      }

      $this->loadComponent('Paginator');
      $session = $this->request->getSession();
      $membres = $this->Paginator->paginate($this->Membre->find()
          ->contain(['Utilisateur'])
          ->where(['idProjet' => $id]));
      $this->set(compact('membres', 'id'));
    }
  }


    /**
    * TODO: sprint 5, envoi notif au membre invité
    * @author POP Diana
    */
    public function add($id){
      if ($this->request->is('post')){

      // Est-ce que l'utilisateur demandé existe ?
          $utilisateurs = TableRegistry::get('Utilisateur');
          $query = $utilisateurs->find()
              ->select(['idUtilisateur'])
              ->where(['pseudo' => $this->request->getData()['recherche_utilisateurs']])
              ->first();
          $id_utilisateur = $query['idUtilisateur'];

        if ($id_utilisateur===null){
          $this->Flash->error(__('Ce membre n\'existe pas.'));
          return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id]);
        }

        // Est-ce que l'utilisateur est propriétaire du projet ?
        $session = $this->request->getSession(); // Le check Session est vrai car on est passés par index de ce même controller
        if ($id_utilisateur===$session->read('Auth.User.idUtilisateur')){
          $this->Flash->error(__('Vous êtes le propriétaire de ce projet.'));
          return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id]);
        }

        // Est-ce que l'utilisateur demandé est déjà dans le projet ?
        $count = $this->Membre->find()->where(['idUtilisateur'=>$id_utilisateur, 'idProjet'=>$id])->count();
        if ($count>0){
          $this->Flash->error(__('Ce membre est déjà dans le projet.'));
          return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id]);
        }

        // Bienvenue au nouveau membre dans le projet !
        $membre = $this->Membre->newEntity();

        $membre->idProjet= $id;
        $membre->idUtilisateur= $id_utilisateur;

        if ($this->Membre->save($membre)) {
          $this->Flash->success(__('Le membre a été ajouté à la liste.'));

          return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id]);
        }
        $this->Flash->error(__('Impossible d\'ajouter ce membre.'));
      } // fin if post
    }

    /**
    * TODO si proprio ne pas supprimer
    *
    * Auteur : POP Diana
    */
    public function delete($id_utilisateur, $id_projet){
      // Comme session ne marche pas, on va aller chercher l'idPropriétaire du projet.
      $projets = TableRegistry::get('Projet');
      $projet = $projets->find()->where(['idProjet'=>$id_projet])->first();
      $id_proprio = $projet->idProprietaire;

      if ($id_utilisateur==$id_proprio){
        return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id_projet]);
      }else{
        $membre = $this->Membre->find()->where(['idUtilisateur'=>$id_utilisateur, 'idProjet'=>$id_projet])->first();
        $success = $this->Membre->delete($membre);
        return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id_projet]);
      }
    }

    public function edit($id_utilisateur, $id_projet){
      return $this->redirect(['action'=> 'index', $id_projet]);
    }

}

?>
