<?php
namespace App\Controller;

class TacheController extends AppController
{

  

    public function index()
    {
        $this->loadComponent('Paginator');
        $taches = $this->Paginator->paginate($this->Tache->find()->where());
        $this->set(compact('taches'));
    }
}
?>
