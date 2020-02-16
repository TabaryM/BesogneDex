<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;

class AccueilController extends AppController
{

  
  public function index()
  {
     $this->loadComponent('Paginator');
  }

}
?>
