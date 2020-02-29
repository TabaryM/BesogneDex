<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

class AccueilController extends AppController
{


  public function index()
  {
     $this->loadComponent('Paginator');
  }

  public function unauthorized(){
    $this->Flash->error(__('Vous n\'avez pas accès à cette page.'));

    return $this->redirect(['action'=> 'index', $id]);
  }

}
?>
