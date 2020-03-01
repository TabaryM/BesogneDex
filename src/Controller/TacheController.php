<?php
namespace App\Controller;

require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'AffichageErreurs.php');
use App\Controller\AppController;
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


    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
          $user = $session->read('Auth.User.idUtilisateur');
    }

    $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
    ->get('Projet')->find()
    ->where(['idProjet' => $idProjet])
    ->first();


    //Pour la couronne dans le header
    Configure::write('utilisateurProprietaire', false);
    $this->loadComponent('Paginator');

    $taches = $this->Paginator->paginate($this->Tache->find()
    ->contain('Utilisateur')
    ->where(['idProjet' => $idProjet]));

    //Regarde si l'utilisateur est autorisé à acceder au contenu
    $estProprietaire = $this->autorisation($idProjet);

    // fin session check idUtilisateur
    $this->set(compact('taches', 'idProjet', 'projetTab', 'estProprietaire', 'user'));

  } // fin fonction

  /**
  * Permet d'afficher les détails d'un projet (Description + liste des membres)
  * @author Thibault Choné, Théo Roton
  * @param $id : id du projet cliqué ou affiché
  */
  public function details($idProjet)
  {

    $this->autorisation($idProjet);

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
  * @author Clément COLNE, Adrien Palmieri
  */
  public function add($idProjet){
    if ($this->request->is('post')){
      $data = $this->request->getData();
      $data['idProjet'] = $idProjet;
      $tache = $this->Tache->newEntity($data);
      $tache->finie = 0;
      $tache->idProjet = $idProjet;

      if(empty($tache->titre)){
        $this->Flash->error(__('Impossible d\'ajouter une tâche avec un nom vide.'));
        return $this->redirect(['action'=> 'add', $idProjet]);
      }
      // On verifie qu'il n'existe pas une tache du meme nom
      foreach($this->Tache->find('all', ['conditions'=>['idProjet'=>$idProjet]]) as $task) {
        if($task->titre == $tache->titre) {
          $this->Flash->error(__('Impossible d\'avoir plusieurs taches avec le meme nom.'));
          return $this->redirect(['action'=> 'add', $idProjet]);
        }
      }
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
    $data = $this->request->getData();
    if(isset($data) && !empty($data)){
      $tache = $this->Tache->find()
      ->where(['idTache' => $idTache])
      ->first();

      $data = array_filter($data, function($value) { return !is_null($value) && $value !== '' && !empty($value); }); //On supprime les éléments vide

      $data['idProjet'] = $idProjet;

      $tache = $this->Tache->get($idTache); //On récupère les données tâches
      $data2 = $this->Tache->patchEntity($tache, $data); //On "assemble" les données entre data et une tâche

      if($this->Tache->save($data2)){ //On sauvegarde les données (Le vérificator passe avant)
        $this->Flash->success(__('La Tâche a été modifié.'));
      }else{
        $errors = affichage_erreurs($tache->errors(), $this);
        print_r($data2);
        if(!empty($errors)){ //TODO: Factoriser ?
          $this->Flash->error(
            __("Veuillez modifier ce(s) champs : ".implode("\n \r", $errors))
          );
        }
      }//TODO: redirect en casde succès
    }

    $id = $idProjet;

    $this->set(compact('id'));
  }

  /**
  * Permet à un membre de projet de devenir responsable d'une tache
  * @author Mathieu TABARY
  */
  public function devenirResponsable($idProjet, $idTache) {
    $session = $this->request->getSession();
    $tache = $this->Tache->get($idTache);
    $tache->idResponsable = $session->read('Auth.User.idUtilisateur');
    $this->Tache->save($tache);
    // TODO: Envoyer notification aux autres membres du projet
    return $this->redirect(['action' => 'index', $idProjet]);
  }

  /**
  * Utilisée dans Template/Tache/index.ctp
  * lors de la suppression d'une tâche.
  * @author WATELOT Paul-Emile
  */
  public function delete($idProjet, $idTache){
    $this->set(compact('idProjet','idTache'));

    $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
    ->get('Projet')->find()
    ->where(['idProjet' => $idProjet])
    ->first();

    //permet de savoir si un utilisateur est propriétaire du projet
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      $tacheTab = TableRegistry::getTableLocator()->get('Tache');
      $tache = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
      ->get('Tache')->find()
      ->where(['idTache' => $idTache])
      ->first();
      //si il est propriétaire du projet ou que l'utilisateur est responsable de la tache il peut supprimer cette tache
      if($projetTab->idProprietaire == $user || $tache->idResponsable == $user){
        $query = $tacheTab->query();
        $query->delete()->where(['idTache' => $idTache])->execute();
      }
    }

    return $this->redirect(['action' => 'index', $idProjet]);
  }

  /**
  * Permet a un membre du projet de se retirer d'une tâche
  * @author Adrien Palmieri
  */
  public function notSoResponsible($idProjet, $idTache) {
    $tache = $this->Tache->get($idTache);
    $tache->idResponsable = NULL;
    $this->Tache->save($tache);
    return $this->redirect(['action' => 'index', $idProjet]);
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

  private function autorisation($idProjet){

    $projetTab = TableRegistry::getTableLocator() //On récupère la table Projet pour en extraire les infos
    ->get('Projet')->find()
    ->where(['idProjet' => $idProjet])
    ->first();

    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      if($projetTab->idProprietaire == $user){
        //Pour la couronne dans le header
        Configure::write('utilisateurProprietaire', true);

        return true;
        // S'il n'est pas propriétaire, est-il membre ?
        // -> Vérifie en même temps si le projet existe.
      }else{
        $membres = TableRegistry::get('Membre');
        $query = $membres->find()
        ->select(['idUtilisateur'])
        ->where(['idUtilisateur' => $user, 'idProjet' => $idProjet])
        ->count();
        // S'il n'est pas membre non plus, on le redirige.
        if ($query==0){
          $this->Flash->error(__('Ce projet n\'existe pas ou vous n\'y avez pas accès.'));
          return $this->redirect(['controller'=>'Accueil', 'action'=>'index']);
        }
      }
    }
    //return $this->redirect(['controller'=>'Pages', 'display'=>'home']);;
  }

}

?>
