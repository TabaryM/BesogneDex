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


}
?>
