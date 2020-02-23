<?php
namespace App\Controller;

require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'VerificationChamps.php');
use Cake\ORM\TableRegistry;

class ProjetController extends AppController
{
  /**
  *  Affiche la liste des projets dont l'utilisateur est membre.
  *
  * TODO: Il faut afficher les listes dont l'utilisateur est membre et non celles pour lesquelles il est propriétaire.
  * Auteur : POP Diana
  */
    public function index()
    {
        $this->loadComponent('Paginator');
        $session = $this->request->getSession();
        // $projets = $this->Paginator->paginate($this->Projet->find()->contain(['Membre'])->where(['idUtilisateur' => $session->read('Auth.User.idUtilisateur')]));
        $projets = $this->Paginator->paginate($this->Projet->find()->distinct()->contain('Utilisateur')
        ->leftJoinWith('Membre')
        ->where(
          ['etat !=' => 'Archive', 'OR' => [
              'Membre.idUtilisateur' => $session->read('Auth.User.idUtilisateur'),
              'Projet.idProprietaire' => $session->read('Auth.User.idUtilisateur')
         ]]));

        $this->set(compact('projets'));
    }

    /**
    * Crée un projet dont l'utilisateur connecté sera le propriétaire.
    *
    * @authors : POP Diana, TABARY Mathieu
    */
    public function add(){
      if ($this->request->is('post')){
        $receivedData = $this->request->getData();

          // Vérification des saisies utilisateurs
          if(verification_titre($receivedData['titre'])){
              if(verification_description($receivedData['description'])){
                  if(verification_dates($receivedData['dateDebut'], $receivedData['dateFin'])){
                      $dateDuJour = getdate();
                      if($receivedData['dateDebut'] == $receivedData['dateFin']){

                      }
                      $projet = $this->Projet->newEntity($receivedData);
                      $session = $this->request->getSession();
                      $projet->idProprietaire = $session->read('Auth.User.idUtilisateur');

                      if ($this->Projet->save($projet)) {
                          $this->Flash->success(__('Votre projet a été sauvegardé.'));

                          return $this->redirect(['action'=> 'index']);
                      }
                      // Si il y a eu une erreur lors de l'ajout dans la database
                      $this->Flash->error(__("Impossible d'ajouter votre projet."));

                  } else {
                      // Si les dates ne sont pas cohérentes
                      $this->Flash->error(__("La fin du projet ne peut pas se faire avant le début de ce projet"));
                  }

              } else {
                  // Si la description n'est pas correcte
                  $this->Flash->error(__("La description est trop longue"));
              }

          } else {
              // Si le titre n'est pas correcte
            $this->Flash->error(__("Titre incorrecte (doit avoir entre 1 et 128 caractères)"));
          }
      }
    }

    /**
    * liste les projets archivés
    *
    * Auteurs : WATELOT Paul-Emile
    */
    public function archives(){
      $projets = TableRegistry::getTableLocator()->get('Projet');

      $session = $this->request->getSession();
      $archives = $projets->find()->distinct()->contain('Utilisateur')
      ->leftJoinWith('Membre')
      ->where(
        ['etat' => 'Archive', 'OR' => [
            'Membre.idUtilisateur' => $session->read('Auth.User.idUtilisateur'),
            'Projet.idProprietaire' => $session->read('Auth.User.idUtilisateur')
       ]]);

      $this->set(compact('archives'));

    }

    /**
    * Supprime un projet si propriétaire et enleve un membre du groupe si il quitte
    *
    * Auteurs : WATELOT Paul-Emile
    */
    public function delete($id){
      if ($this->request->is('post')){

        $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
          ->get('Projet')->find()
          ->where(['idProjet' => $id])
          ->first();

        //permet de savoir si un utilisateur est propriétaire
        $session = $this->request->getSession();
        if ($session->check('Auth.User.idUtilisateur')) {
          $user = $session->read('Auth.User.idUtilisateur');
          if($projetTab->idProprietaire == $user){

            //degage tout les membres du projet
            $membres = TableRegistry::getTableLocator()->get('Membre');
            $query = $membres->query();
            $query->delete()->where(['idProjet' => $id])->execute();

            //supprime les taches du projet
            $taches = TableRegistry::getTableLocator()->get('Tache');
            $query = $taches->query();
            $query->delete()->where(['idProjet' => $id])->execute();

            //supprime le projet
            $projets = TableRegistry::getTableLocator()->get('Projet');
            $query = $projets->query();
            $query->delete()->where(['idProjet' => $id])->execute();

          }
          //sinon si c'est un invité on le degage dans la table membre
          else{
            $membres = TableRegistry::getTableLocator()->get('Membre');
            $query = $membres->query();
            $query->delete()->where(['idProjet' => $id, 'idUtilisateur' => $user])->execute();
          }
        }

        return $this->redirect(['action'=> 'index']);
      }
    }
}
?>
