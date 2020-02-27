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
     * @param $idProjet : id du projet cliqué ou affiché
     */
    public function index($idProjet)
    {

      $estProprietaire = false;
      $user = null;


      //Pour la couronne dans le header
      Configure::write('utilisateurProprietaire', false);
      $this->loadComponent('Paginator');

      $taches = $this->Paginator->paginate($this->Tache->find()
      ->contain('Utilisateur')
      ->where(['idProjet' => $idProjet]));

      $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
        ->get('Projet')->find()
        ->where(['idProjet' => $idProjet])
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
            $this->set(compact('taches', 'idProjet', 'projetTab', 'estProprietaire', 'user'));


    }// fin fonction

    /**
     * Permet d'afficher les détails d'un projet (Description + liste membres)
     * @author Thibault Choné
     * @param $idProjet : id du projet cliqué ou affiché
     */
    public function details($idProjet)
    {
        $projets = TableRegistry::getTableLocator()->get('Projet');
        $projet = $projets->find()->where(['idProjet' => $idProjet])->first();
        $desc = $projet->description;

        $membres = TableRegistry::getTableLocator()->get('Membre');
        $membres = $membres->find()->contain('Utilisateur')
        ->where(['idProjet' => $idProjet]);

        $mbs = "";
        foreach ($membres as $m) {
          $mbs .= $m->un_utilisateur->pseudo . "<br>";
        }

        $this->set(compact('desc', 'idProjet', 'mbs'));
    }

    /**
     * Ajoute une ligne dans la table tache
     * @author Clément COLNE
     */
    public function add($idProjet){
      if ($this->request->is('post')){
        $tache = $this->Tache->newEntity($this->request->getData());
        $tache->finie = 0;
        $tache->idProjet = $idProjet;
        if ($this->Tache->save($tache)) {
          $this->Flash->success(__('Votre tâche a été sauvegardée.'));
          if($tache->estResponsable == 1) {
            // l'utilisateur devient responsable de la tâche
            $this->devenirResponsable($idProjet, $tache->idTache);
          }
          return $this->redirect(['action'=> 'index', $idProjet]);
        }
        $this->Flash->error(__('Impossible d\'ajouter votre tâche.'));
      }
      $this->set(compact('idProjet'));
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
        $this->redirect($this->referer());
      }
    }

    /**
    * Utilisée dans : Template/Tache/index.ctp
    */
    public function edit($idProjet)
    {
        $this->set(compact('idProjet'));
}

    /**
     * Permet à un membre de projet de devenir responsable d'une tache
     * @author Mathieu TABARY
     */
    public function devenirResponsable($idProjet, $idTache) {
        $session = $this->request->getSession();

        $this->Tache->updateAll(
            ['idResponsable' => $session->read('Auth.User.idUtilisateur')],
            ['idTache' => $idTache]
        );
        // TODO: Envoyer notification aux autres membres du projet
        return $this->redirect(['action' => 'index', $idProjet]);
    }

    /**
    * Utilisée dans Template/Tache/index.ctp
    * lors de la suppression d'une tâche.
     * @author WATELOT Paul-Emile
    */
    public function delete($idProj, $idTach){
        $this->set(compact('idTach'));

        $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
        ->get('Projet')->find()
            ->where(['idProjet' => $idProj])
            ->first();

        //permet de savoir si un utilisateur est propriétaire du projet
        $session = $this->request->getSession();
        if ($session->check('Auth.User.idUtilisateur')) {
            $user = $session->read('Auth.User.idUtilisateur');
            $tacheTab = TableRegistry::getTableLocator()->get('Tache');
            $tache = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
            ->get('Tache')->find()
                ->where(['idTache' => $idTach])
                ->first();
            //si il est propriétaire du projet ou que l'utilisateur est responsable de la tache il peut supprimer cette tache
            if($projetTab->idProprietaire == $user || $tache->idResponsable == $user){
                $query = $tacheTab->query();
                $query->delete()->where(['idTache' => $idTach])->execute();
            }
        }
    }

    /**
     * Permet a un membre du projet de se retirer d'une tâche
     * @author Adrien Palmieri
     */
    public function notSoResponsible($idProjet, $idTache) {
        $session = $this->request->getSession();
        $tache = $this->Tache->get($idTache);
        $tache->idResponsable = NULL;
        $this->Tache->save($tache);
        return $this->redirect(['action' => 'index', $idProjet]);
    }



    public function finie($idProjet, $idTache){

      return $this->redirect(['action' => 'index', $idProjet]);
    }
}

?>
