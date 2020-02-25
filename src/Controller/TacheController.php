<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class TacheController extends AppController
{
    /**
     * Affichage d'un projet avec sa liste de tâches (en fonction de l'id donnée).
     * Redirige vers l'accueil si le projet n'existe pas ou si la personne n'en est pas membre.
     *
     * @author Thibault Choné, POP Diana
     * @param $id : id du projet cliqué ou affiché
     */
    public function index($id)
    {

      $estProprietaire = false;
      //Pour la couronne dans le header
      Configure::write('utilisateurProprietaire', false);
      $this->loadComponent('Paginator');

      $taches = $this->Paginator->paginate($this->Tache->find()
      ->contain('Utilisateur')
      ->where(['idProjet' => $id]));

      $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
        ->get('Projet')->find()
        ->where(['idProjet' => $id])
        ->first();

      $session = $this->request->getSession();
      if ($session->check('Auth.User.idUtilisateur')) {
        $user = $session->read('Auth.User.idUtilisateur');
        if($projetTab->idProprietaire == $user){
          $estProprietaire = true;
          //Pour la couronne dans le header
          Configure::write('utilisateurProprietaire', true);

        // S'il n'est pas propriétaire, est-il membre ?
        // -> Vérifie en même temps si le projet existe.
      }else{
        $membres = TableRegistry::get('Membre');
        $query = $membres->find()
            ->select(['idUtilisateur'])
            ->where(['idUtilisateur' => $user])
            ->count();
            // S'il n'est pas membre non plus, on le redirige.
        if ($query==0){
          $this->Flash->error(__('Ce projet n\'existe pas ou vous n\'y avez pas accès.'));
          $this->redirect(['controller'=>'Accueil', 'action'=>'index']);
        }
      }

    }// fin session check idUtilisateur
      $this->set(compact('taches', 'id', 'projetTab', 'estProprietaire'));
    }// fin fonction

    /**
     * Permet d'afficher les détails d'un projet (Description + liste membres)
     * @author Thibault Choné
     * @param $id : id du projet cliqué ou affiché
     */
    public function details($id)
    {
        $projets = TableRegistry::getTableLocator()->get('Projet');

        $projet = $projets->find()->where(['idProjet' => $id])->first();

        $desc = $projet->description;

        //TODO:Liste membre à afficher

        $this->set(compact('desc', 'id'));
    }

    /**
     * Ajoute une ligne dans la table tache
     * @author Clément COLNE
     */
    public function add($id){
      if ($this->request->is('post')){
        $tache = $this->Tache->newEntity($this->request->getData());
        $tache->finie = 0;
        $tache->idProjet = $id;
        if ($this->Tache->save($tache)) {
          $this->Flash->success(__('Votre tâche a été sauvegardée.'));
          if($tache->estResponsable == 1) {
            // l'utilisateur devient responsable de la tâche
            $this->devenirResponsable($id, $tache->idTache);
          }
          return $this->redirect(['action'=> 'index', $id]);
        }
        $this->Flash->error(__('Impossible d\'ajouter votre tâche.'));
      }
      $this->set(compact('id'));
    }

    /**
     * Affiche toutes les tâches de l'utilisateur
     *
     * @author Pedro
     */
    public function my() {
      $session = $this->request->getSession();
      if ($session->check('Auth.User.idUtilisateur')) {
        $user = $session->read('Auth.User.idUtilisateur');
        $taches = $this->Tache->find()
          ->contain(['Utilisateur', 'Projet'])
          ->where(['idResponsable' => $session->read('Auth.User.idUtilisateur')])->toArray();

        $this->set(compact('taches'));
      } else {
        $this->Flash->error(_('Une erreur est survenue lors de la récupérations des tâches.'));
        $this->redirect($this->referer);
      }
    }

    /**
    * Utilisée dans : Template/Tache/index.ctp
    */
    public function edit($id)
    {
        $this->set(compact('id'));
    }

    /**
     * Permet à un membre de projet de devenir responsable d'une tache
     * @author Mathieu TABARY
     */
    public function devenirResponsable($id, $idTache) {
        // TODO demander au front-end de griser si c'est déjà pris
        $session = $this->request->getSession();

        $this->Tache->updateAll(
            array('idResponsable' => $session->read('Auth.User.idUtilisateur')),
            array('idTache' => $idTache)
        );
        return $this->redirect(['action' => 'index', $id]);
    }

    /**
    * Utilisée dans Template/Tache/index.ctp
    * lors de la suppression d'une tâche.
    */
    public function delete($id){
        $this->set(compact('id'));
    }

    public function finie($idTache){
      echo "Fonction pas terminée ..";
    }
}

?>
