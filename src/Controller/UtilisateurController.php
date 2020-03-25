<?php
namespace App\Controller;

require(__DIR__ . DIRECTORY_SEPARATOR . 'Component' . DIRECTORY_SEPARATOR . 'listeErreursVersString.php');
use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;

class UtilisateurController extends AppController
{

  public function initialize()
  {
    parent::initialize();
  }

  public function index(){
    return $this->redirect(['action'=> 'profil']);
  }

  private function afficherErreurs($erreursAAfficher){
    if(!empty($erreursAAfficher)){
      $this->Flash->error(
        __("Veuillez modifier ce(s) champs : ".implode("\n \r", $erreursAAfficher))
      );
    }
  }

  /**
  * Pris de la doc officielle :
  * Permet les utilisateurs de s'inscrire et de se déconnecter.
  * La doc demande à ne pas ajouter 'login' dans la liste pour ne pas causer de problèmes avec le fonctionnement normal de AuthComponent.
  * @author POP Diana
  */
  public function beforeFilter(Event $event)
  {
    parent::beforeFilter($event);
    $this->Auth->allow(['add', 'logout']);
  }

  /**
  * Permet à l'utilisateur de se connecter.
  * Les pages qui appellent cette fonction sont : Template/Element/header.ctp et Template/Utilisateur/login.ctp.
  * @author POP Diana
  */
  public function login(){
    if ($this->request->is('post')){
      $utilisateur = $this->Auth->identify();
      if ($utilisateur){
        $this->Auth->setUser($utilisateur);

        /* Augmente le temps de session si la case "Rester connecté" a été cochée */
        if ($this->request->getData(['resterConnecte'])==1){
          Configure::write('Session', ['timeout' => 24*60*30 ]);
        }
        $this->Flash->success(__('Vous êtes connecté/e !'));
        return $this->redirect(['controller' => 'Accueil', 'action' => 'index']);
      }else{
        $this->Flash->error(__('E-mail ou mot de passe incorrects'));
        return $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));
      }

    } else {
      return $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));
    }
  }

  /**
  * Permet à l'utilisateur de s'inscrire.
  *
  * La fonction est appelée au clic sur 'Créer mon compte' dans Template/Pages/home.ctp.
  *
  * Si l'inscription a été validée, l'utilisateur est automatiquement connecté.
  *
  * @param /
  * @return /
  * Redirection : Template/Pages/home.ctp
  *
  * @author POP Diana
  */
  public function add(){
    $utilisateur = $this->Utilisateur->newEntity();

    if ($this->request->is('post')) {
      $utilisateur = $this->Utilisateur->patchEntity($utilisateur, $this->request->getData());

      // Si l'inscription est bien réalisée.
      if ($this->Utilisateur->save($utilisateur)) {
        $this->Flash->success(__('Votre compte est bien enregistré.'));

        // On connecte l'utilisateur et on le redirige.
        $this->Auth->setUser($utilisateur);
        $this->redirect(['controller' => 'Accueil', 'action' => 'index']);

      // Si l'inscription a eu des erreurs et ne s'est donc pas faite.
      }else{
          $erreurs = listeErreursVersString($utilisateur->errors());
          $this->afficherErreurs(listeErreursVersString($utilisateur->errors()));
          $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));

      // Redirige sur la page d'inscription si ce n'est pas un POST.
      }
    }else{
        $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));
    }
  }

  /**
  * Fonction pour auto-complétion de Membre/index
  * @author POP Diana (c'est un presque c/c de ce site : http://www.naidim.org/cakephp-3-tutorial-18-autocomplete)
  */
  function complete(){
    $this->autoRender = false;
    $name = $this->request->query['term'];
    $results = $this->Utilisateur->find('all', [
      'conditions' => [
        'pseudo LIKE' => $name.'%',
      ]
    ]);
    $resultsArr = [];
    foreach ($results as $result) {
      $resultsArr[] =['label' => $result['pseudo'], 'value' => $result['pseudo']];
    }
    echo json_encode($resultsArr);
  }

  /**
  * Permet à l'utilisateur de se déconnecter.
  * La page qui appelle cette fonction est : Template/Element/Utilisateur/logout_confirmation.ctp
  * @author POP Diana
  */
  public function logout(){
    return $this->redirect($this->Auth->logout());
  }

  /**
  * Utilisée dans la page : Template/Element/header.ctp
  * @author MARISSENS Valérie
  */
  public function logoutConfirmation(){
    return null;
  }

  /**
  * Récupère les données d'un utilisateur pour les rendre disponible dans la page de profil utilisateur
  * Charge la page Utilisateur/profil dans le fichier profil.ctp
  * @author Mathieu TABARY
  */
  public function profil(){
    // Récupère le cookie de session
    $session = $this->request->getSession();
    // Récupère la table utilisateur
    $utilisateurs = TableRegistry::getTableLocator()->get('utilisateur');

    // Récupère les données de l'utilisateur connecté
    $utilisateur = $utilisateurs->find()
        ->where(['idUtilisateur' => $session->read('Auth.User.idUtilisateur')])
        ->first();

    // On enregistre les données de l'utilisateur connecté dans une varible réutilisable dans le fichier .ctp
    $this->set(compact('utilisateur'));
  }

  /**
  * Enregistre les nouvelles informations dans la base de données.
  * Toute les informations modifiés sont dans le getData() data = [nom, prenom, pseudo, mdpActu, mdpNew, mdpNewConf]
  * @author Thibault CHONÉ - Clément COLNE
  */
  public function edit(){
    $session = $this->request->getSession();
    $data = $this->request->getData();
    $utilisateur = $this->Utilisateur->find()
        ->where(['idUtilisateur' => $session->read('Auth.User.idUtilisateur')])
        ->first();

    $estModifie = false;


    if(!empty($data)){
      if(!empty($data['mdpActu'])){ //Si le mdp actuel est vide => erreur
        if((new DefaultPasswordHasher)->check($data['mdpActu'], $utilisateur['mdp'])) { //Si le mdp actuel hashé != du mdp du comtpe => erreur
          if($data['mdpNew'] == $data['mdpNewConf']) {

            if($utilisateur['pseudo'] == $data['pseudo']){ //Si le pseudo n'a pas changé alors on a pas besoin de préciser la modification
              unset($data['pseudo']); //sinon il y a une erreur de pseudo identiques
            }

            $data['mdp'] = $data['mdpNew']; //On affecte le bon nom dans la base de données
            $data['mdpConfirm'] = $data['mdpNewConf']; //On affecte le bon nom dans la base de données

            $data = array_filter($data, function($value) { return !is_null($value) && $value !== '' && !empty($value); }); //On supprime les éléments vide

            $utilisateur = $this->Utilisateur->get($session->read('Auth.User.idUtilisateur')); //On récupère les données utilisateurs
            $data2 = $this->Utilisateur->patchEntity($utilisateur, $data); //On "assemble" les données entre data et utilisateur
            if($this->Utilisateur->save($data2)){ //On sauvegarde les données dans la bdd (Le vérificator passe avant)
              $estModifie = true;
            }

            $erreurs = listeErreursVersString($utilisateur->errors()); //Si il y a des erreurs on les affiche
            $this->afficherErreurs($erreurs);

          }else{
            $this->Flash->error(__('La confirmation de mot de passe est erronée.'));
          }
        }else{
          $this->Flash->error(__('Le mot de passe actuel est incorrect '));
        }
      }else{
        $this->Flash->error(__('Veuillez saisir votre mot de passe pour modifier vos informations.'));
      }
    }


    if($estModifie){
      $this->Flash->success(__('Votre compte est bien enregistré.'));
      return $this->redirect(['action'=> 'profil']);
    }

    $this->set(compact('utilisateur'));
  }

  /**
  * Supprime le compte de l'utilisateur ainsi que les données associées
  * @author PALMIERI Adrien
  */
  public function deleteAccount() {
    $currentUserId = $this->request->getSession()->read('Auth.User.idUtilisateur');
    $utilisateur = $this->Utilisateur->get($currentUserId);

    if(empty($utilisateur)) {
      $this->Flash->error(__('Impossible de supprimer votre compte utilisateur : vérifiez qu\'il existe et que vous êtes bien connecté/e.'));
    } else {

      // Unassign user from tasks where he was assigned
      $tasksUsers = TableRegistry::getTableLocator()
          ->get('Tache')->find()
          ->where(['idResponsable' => $utilisateur->idUtilisateur])
          ->all();

      if(!empty($tasksUsers)) {
        foreach($tasksUsers as $taskUser) { // All the tasks where the user was responsible are now unassigned
          $taskUser->idProprietaire = null;
          TableRegistry::getTableLocator()->get('Tache')->save($taskUser);
        }
      }

      $success = $this->Utilisateur->delete($utilisateur);

      if($success) {
        $this->Auth->logout();
        $this->Flash->success(__('Vous avez supprimé votre compte avec succès'));
        $this->redirect(array('controller' => 'pages', 'action' => 'display','home'));
      } else {
        $this->Flash->error(__('Impossible de supprimer votre compte utilisateur.'));
        $this->redirect(array('controller' => 'Utilisateur', 'action'=> 'edit'));
      }
    }
  }
}

?>
