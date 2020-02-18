<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

class TacheController extends AppController
{

    /**
     * Affichage d'un projet avec sa liste de tâches (en fonction de l'id donnée)
     * TODO : Ne pas afficher le projet si l'utilisateur n'en est pas membre (modification de l'url)
     *       -> à faire quand on aura géré 'Inviter un membre'.
     * @author Thibault Choné
     */
    public function index($id)
    {
        $this->loadComponent('Paginator');

        $taches = $this->Paginator->paginate($this->Tache->find()
        ->contain('Utilisateur')
        ->where(['idProjet' => $id]));
        $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
          ->get('Projet')->find()
          ->where(['idProjet' => $id])
          ->first();
        $this->set(compact('taches', 'id', 'projetTab'));
    }

    /**
     * Permet d'afficher les détails d'un projet (Description + liste membres)
     * @author Thibault Choné
     */
    public function details($id)
    {
        $projets = TableRegistry::getTableLocator()->get('Projet');

        $projet = $projets->find()->where(['idProjet' => $id])->first();

        $desc = $projet->description;

        //TODO:Liste membre à afficher

        $this->set(compact('desc', 'id'));
    }

    /**
     * Ajoute une ligne dans la table tache
     * @author Clément
     */
    public function add($id){
      if ($this->request->is('post')){
        $tache = $this->Tache->newEntity($this->request->getData());
        $tache->finie = 0;

        $tache->idProjet = $id;

        if ($this->Tache->save($tache)) {
          $this->Flash->success(__('Votre tâche a été sauvegardée.'));

          return $this->redirect(['action'=> 'index', 'id' => $tache->idProjet]);
        }
        $this->Flash->error(__('Impossible d\'ajouter votre tâche.'));
      }
    }

    /**
    * Affiche les membres d'un projet.
    *
    * Auteur : POP Diana
    */
    public function manageMembers($id){
      $projet = $projets->find()->where(['idProjet' => $id])->first();
    }

    /**
     * Affiche toutes les tâches de l'utilisateur
     *
     * @author Pedro
     */
    public function my() {
      $session = $this->request->getSession();
      if ($session->check('Auth.User.idUtilisateur')) {
        $user = $session->read('Auth.User.idUtilisateur');
        $taches = $this->Tache->find()
          ->contain(['Utilisateur', 'Projet'])
          ->where(['idResponsable' => $session->read('Auth.User.idUtilisateur')])->toArray();

        $this->set(compact('taches'));
      } else {
        $this->Flash->error(_('Une erreur est survenue lors de la récupérations des tâches.'));
        $this->redirect($this->referer);
      }
    }

}
?>
