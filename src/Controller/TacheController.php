<?php
namespace App\Controller;

class TacheController extends AppController
{

    public function index()
    {
        $this->loadComponent('Paginator');
        $id = $this->request->query['id'];
        $taches = $this->Paginator->paginate($this->Tache->find()->where(['idProjet' => $id]));
        $this->set(compact('taches'));
    }

    public function add(){
      if ($this->request->is('post')){
        $tache = $this->Tache->newEntity($this->request->getData());
        $tache->finie = 0;
        $tache->idProjet = 4;

        if ($this->Tache->save($tache)) {
          $this->Flash->success(__('Votre tâche a été sauvegardée.'));

          return $this->redirect(['action'=> 'index', 'id' => $tache->idProjet]);
        }
        $this->Flash->error(__('Impossible d\'ajouter votre tâche.'));
      }
    }

}
?>