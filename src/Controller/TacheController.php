<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

class TacheController extends AppController
{

    public function index()
    {
        $this->loadComponent('Paginator');
        $id = $this->request->query['id'];
        $taches = $this->Paginator->paginate($this->Tache->find()->where(['idProjet' => $id]));
        $this->set(compact('taches', 'id'));
    }

    public function details()
    {
        $id = $this->request->query['id'];

        $projets = TableRegistry::getTableLocator()->get('Projet');

        $query = $projets->find()->where(['idProjet' => $id]);

        $desc = "";

        foreach ($query as $row) {
            $desc = $row->description;
        }

        $this->set(compact('desc', 'id'));
    }


}
?>
