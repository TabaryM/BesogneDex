<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

class ProjetController extends AppController
{
    public function index()
    {
        $this->loadComponent('Paginator');
        $projets = $this->Paginator->paginate($this->Projet->find());
        $this->set(compact('projets'));
    }

    public function add(){
      if ($this->request->is('post')){
        $projet = $this->Projet->newEntity($this->request->getData());

        if ($this->Projet->save($projet)) {
          $this->Flash->success(__('Votre projet a été sauvegardé.'));

          return $this->redirect(['action'=> 'index']);
        }
        $this->Flash->error(__('Impossible d\'ajouter votre projet.'));
      }
    }
}
