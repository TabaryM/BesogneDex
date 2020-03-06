<?php
namespace App\Controller;
require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'VerificationChamps.php');
require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'listeErreursVersString.php');
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\I18n\Time;

class TacheController extends AppController
{
  /**
  * Affichage d'un projet avec sa liste de tâches (en fonction de l'id donnée).
  * Redirige vers l'accueil si le projet n'existe pas ou si la personne n'en est pas membre sinon affiche la liste des tâches.
  * @author Thibault Choné, POP Diana
  * @param  int $idProjet id du projet à afficher
  */
  public function index($idProjet)
  {

    $estProprietaire = false;

    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
        $user = $session->read('Auth.User.idUtilisateur');
    }

    $projetTab = TableRegistry::getTableLocator() // On récupère la table Projet pour en extraire les infos
    ->get('Projet')->find()
    ->where(['idProjet' => $idProjet])
    ->first();

    // Pour la couronne dans le header
    Configure::write('utilisateurProprietaire', false);
    //Pour le nom en rouge quand un projet est expiré
    Configure::write('estExpire', false);

    $today = Time::now();
    if($projetTab->dateFin < $today){
      Configure::write('estExpire', true);
    }

    $this->loadComponent('Paginator');

    $taches = $this->Paginator->paginate($this->Tache->find()
    ->contain('Utilisateur')
    ->where(['idProjet' => $idProjet]));

    // Regarde si l'utilisateur est autorisé à acceder au contenu
    $estProprietaire = $this->autorisation($idProjet);

    // fin session check idUtilisateur
    $this->set(compact('taches', 'idProjet', 'projetTab', 'estProprietaire', 'user'));

  }

  /**
  * @author Thibault Choné, Théo Roton
  * @param idProjet : id du projet pour lequel on affiche les détails
  *
  * Cette fonction affiche les détails, la description et les membres,
  * du projet identifié par son id.
  */
  public function details($idProjet)
  {

    $this->autorisation($idProjet);

    //On récupère la table des projets
    $projets = TableRegistry::getTableLocator()->get('Projet');
    //On récupère le projet identifié par idProjet
    $projet = $projets->find()->where(['idProjet' => $idProjet])->first();
    //On récupère la description du projet
    $desc = $projet->description;

    //On récupère la table des membres
    $membres = TableRegistry::getTableLocator()->get('Membre');
    //On récupère les membres du projet identifié par idProjet
    $membres = $membres->find()->contain('Utilisateur')
    ->where(['idProjet' => $idProjet]);

    $mbs = array();
    foreach ($membres as $m) {
      array_push($mbs,$m->un_utilisateur->pseudo);
    }

    $this->set(compact('desc', 'idProjet', 'mbs'));
  }

  /**
  * Ajoute une ligne dans la table tache
  * @author Clément Colné, Adrien Palmieri
  */
  public function add($idProjet){
    if ($this->request->is('post')) {
      $data = $this->request->getData();
      $data['idProjet'] = $idProjet;

      $data['titre'] = nettoyerTexte($data['titre']);
      $data['description'] = nettoyerTexte($data['description']);

      $tache = $this->Tache->newEntity($data);

      if(!empty($tache->errors()) && $tache->errors() != null){ //TODO: C'est pas propre
        $errors = listeErreursVersString($tache->errors(), $this);
        $this->Flash->error(
          __("Erreurs : ".implode("\n \r", $errors))
        );
      }else{

        $tache->finie = 0;
        $tache->idProjet = $idProjet;
        if(empty($tache->titre)){
          $this->Flash->error(__('Impossible d\'ajouter une tâche avec un nom vide.'));
        }else{
          // On verifie qu'il n'existe pas une tache du meme nom
          foreach($this->Tache->find('all', ['conditions'=>['idProjet'=>$idProjet]]) as $task) {
            if($task->titre == $tache->titre) {
              $this->Flash->error(__('Impossible d\'avoir plusieurs taches avec le meme nom.'));
              return $this->redirect(['action'=> 'add', $idProjet]); //TODO: Pas propre
            }
          }
          if ($this->Tache->save($tache)) {
            $this->Flash->success(__('Votre tâche a été sauvegardée.'));
            if($tache->estResponsable == 1) {
              // l'utilisateur devient responsable de la tâche
              $this->devenirResponsable($idProjet, $tache->idTache);
            }
            return $this->redirect(['action'=> 'index', $idProjet]); //TODO: Pas propre
          }else{
            $this->Flash->error(__('Impossible d\'ajouter votre tâche.'));
          }
        }
      }
    }
    $this->set(compact('idProjet'));
  }

  /**
  * Affiche toutes les tâches de l'utilisateur
  * @author Pedro Sousa Ribeiro
  * Redirection: Si l'utilisateur n'est pas connecté, il est redirigé vers la page d'où il vient.
  *              Sinon il est dirigé vers la liste de ses tâches
  */
  public function my() {
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      $taches = $this->Tache->find()
      ->contain(['Utilisateur', 'Projet'])
      ->where(['idResponsable' => $user])->toArray();

      $this->set(compact('taches'));
    } else {
      $this->Flash->error(_('Une erreur est survenue lors de la récupérations des tâches.'));
      $this->redirect($this->referer());
    }
  }

  /**
   * Utilisée dans : Template/Tache/index.ctp
   * Affiche la page de modification de tâche et traite le formulaire de modification (et le push dans la bdd en cas de succès)
   * Redirect vers la liste des projets si il y a eu une modification effective.
   * @author Thibault Choné
   * @param  int $idProjet id du projet dans lequel se trouve la tâche
   * @param  int $idTache  id de la tâche à modifier
   * @return redirect      Si la modification est effectuée sans erreur
   */
  public function edit($idProjet, $idTache)
  {
    $data = $this->request->getData();

    $tache = $this->Tache->find()
    ->where(['idTache' => $idTache])
    ->first();

    $succes = false;

    if(!empty($data)){
      if(empty($data['titre'])){
          $this->Flash->error(__("Le nom de la tâche ne peut pas être vide."));
      }else{
        $data['titre'] = nettoyerTexte($data['titre']);
        $data['description'] = nettoyerTexte($data['description']);

        $data = array_filter($data, function($value) { return !is_null($value) && $value !== '' && !empty($value); }); //On supprime les éléments vide

        $data['idProjet'] = $idProjet;

        $tache = $this->Tache->get($idTache); //On récupère les données tâches
        $data2 = $this->Tache->patchEntity($tache, $data); //On "assemble" les données entre data et une tâche

        if($this->Tache->save($data2)){ //On sauvegarde les données (Le vérificator passe avant)
          $this->Flash->success(__('La Tâche a été modifié.'));
          $succes = true;
        }else{
          $errors = listeErreursVersString($tache->errors(), $this);
          if(!empty($errors)){ //TODO: Factoriser ?
            $this->Flash->error(
              __("Erreurs : ".implode("\n \r", $errors))
            );
          }
        }
      }
    }
    $titre = $tache['titre'];
    $description = $tache['description'];

    if($succes){
      return $this->redirect(['action'=> 'index', $idProjet]);
    }

    $this->set(compact('idProjet', 'idTache', 'titre', 'description'));
  }

    /**
     * Permet à un membre de projet de devenir responsable d'une tache
     * @param $idProjet int identifiant unique du projet
     * @param $idTache int identifiant unique de la tâche
     * @return \Cake\Http\Response|null Retourne sur la liste des tâches
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
  * @param $idProjet l'id du projet qui contient la tache, $idTache l'id de la tache a supprimer
  * @return redirection vers la page du projet
  */
  public function delete($idProjet, $idTache){
    //donne acces a (Tache/)index.ctp
    $this->set(compact('idProjet','idTache'));

    //On récupère la table Projet et on recupere le projet voulu
    $projetTab = TableRegistry::getTableLocator()
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

        //TODO pour PE: Si c'est le proprio envoyer une notif a tout les membres du projet comme quoi la tache X du projet Y a ete supprimée. sinon envoyer une demande de confirmation au proprio et si il accepte, la supprimer

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
  * Permet de changer l'état d'une tache de "fait" a "non fait" et vis versa. Cette méthode est utilisé par le script JS en Ajax
  * @param int $id ID de la tache dont l'etat est a changer
  * @param boolean $fait Booleen indiquant si la tache est faite ou non
  * @author Pedro Sousa Ribeiro
  *
  * Redirection: aucune
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

  /**
   * TODO : Faire la doc
   */
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
        if ($query == 0){
          $this->Flash->error(__('Ce projet n\'existe pas ou vous n\'y avez pas accès.'));
          return $this->redirect(['controller'=>'Accueil', 'action'=>'index']);
        }
      }
    }
  }

}

?>
