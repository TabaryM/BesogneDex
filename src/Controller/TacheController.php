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
    $user = null;
    
    
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
    $this->set(compact('taches', 'id', 'projetTab', 'estProprietaire', 'user'));
    
    
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
    
    $membres = TableRegistry::getTableLocator()->get('Membre');
    $membres = $membres->find()->contain('Utilisateur')
    ->where(['idProjet' => $id]);
    
    $mbs = "";
    foreach ($membres as $m) {
      $mbs .= $m->un_utilisateur->pseudo . "<br>";
    }
    
    $this->set(compact('desc', 'id', 'mbs'));
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
      $this->redirect($this->referer());
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
    $session = $this->request->getSession();
    
    $this->Tache->updateAll(
      ['idResponsable' => $session->read('Auth.User.idUtilisateur')],
      ['idTache' => $idTache]
    );
    
    // TODO: Envoyer notification aux autres membres du projet 
    return $this->redirect(['action' => 'index', $id]);
  }
  
  /**
  * Utilisée dans Template/Tache/index.ctp
  * lors de la suppression d'une tâche.
  */
  public function delete($id){
    $this->set(compact('id'));
  }
  
  /**
  * Permet a un membre du projet de se retirer d'une tâche
  * @author Adrien Palmieri
  */
  public function notSoResponsible($id, $idTache) {
    $session = $this->request->getSession();
    $tache = $this->Tache->get($idTache);
    $tache->idResponsable = NULL;
    $this->Tache->save($tache);
    return $this->redirect(['action' => 'index', $id]);
  }
  
  /**
  * Permet de changer l'état d'une tache de "fait" a "non fait" et vis versa
  * @param int $id ID de la tache dont l'etat est a changer
  * @param boolean $fait Booleen indiquant si la tache est faite ou non
  * @author Pedro Sousa Ribeiro
  */
  public function changerEtat($id, $fait) {
    // Desactive le rendu de la vue (pas besoin de la vue)
    $this->autoRender = false;
    $this->render(false);
    
    $tache = $this->Tache->get($id);
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      if ($tache->idResponsable === $user) {
        if ($fait) {
          $tache->finie = 1;
        } else {
          $tache->finie = 0;
        }
        $this->Tache->save($tache);
        
      } else {
        $this->Flash->error(__('Seul le responsable de la tâche peut changer l\'état de celui-ci.'));
      }
    } else {
      $this->Flash->error(__('Vous devez être connecté pour changer l\'état d\'une tâche.'));
    }
    
  }
  
  public function finie($idTache){
    echo "Fonction pas terminée ..";
  }
}

?>
