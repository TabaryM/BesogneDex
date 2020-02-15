<?php
namespace App\Controller;

//require('Component\VerificationChamps.php');

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
        $receivedData = $this->request->getData();
          // Vérification des saisies utilisateurs
          if(verification_titre($receivedData['titre']) && verification_description($receivedData['description'])){
              $projet = $this->Projet->newEntity($receivedData);
              $projet->idProprietaire = 1;

              if ($this->Projet->save($projet)) {
                  $this->Flash->success(__('Votre projet a été sauvegardé.'));

                  return $this->redirect(['action'=> 'index']);
              }
              $this->Flash->error(__("Impossible d'ajouter votre projet."));

          } else {
            $this->Flash->error(__("met un nom correct stp ou une jolie description"));
          }
      }
    }
}
?>
