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
     *
     *
     */
    public function index()
    {
        $this->loadComponent('Paginator');
        if (isset($this->request->query['id'])){
          $id = $this->request->query['id'];
        }else{
          die();
          //TODO: affichage erreur (au cas où)
        }

        $taches = $this->Paginator->paginate($this->Tache->find()->where(['idProjet' => $id]));
        $this->set(compact('taches', 'id'));
    }

    /**
     * Permet d'afficher les détails d'un projet (Description + liste membres)
     * @author Thibault Choné
     */
    public function details()
    {
        if (isset($this->request->query['id'])){
          $id = $this->request->query['id'];
        }else{
          die();
          //TODO: affichage erreur (au cas où)
        }
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
    public function add(){
      if ($this->request->is('post')){
        $tache = $this->Tache->newEntity($this->request->getData());
        $tache->finie = 0;
        $id = $this->request->query['id'];
        $tache->idProjet = $id;

        if ($this->Tache->save($tache)) {
          $this->Flash->success(__('Votre tâche a été sauvegardée.'));

          return $this->redirect(['action'=> 'index', 'id' => $tache->idProjet]);
        }
        $this->Flash->error(__('Impossible d\'ajouter votre tâche.'));
      }
    }

}
?>
