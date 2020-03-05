<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;


class MembreController extends AppController
{

  /**
  * Si l'utilisateur n'a pas accès au projet sur lequel il veut effectuer une action, il sera redirigé vers l'accueil.
  *
  * Paramètre : $id est l'idProjet.
  * Retour : aucun.
  * Redirection (si non accès) : index du controller Accueil.
  *
  * @author POP Diana
  */
  public function autorisation($id){
    $estProprietaire = false;
    $this->loadComponent('Paginator');

    //On récupère la table Projet pour en extraire les infos
    $projetTab = TableRegistry::getTableLocator()
      ->get('Projet')->find()
      ->where(['idProjet' => $id])
      ->first();

    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $user = $session->read('Auth.User.idUtilisateur');
      if($projetTab->idProprietaire == $user){
        $estProprietaire = true;
    }else{
        $this->Flash->error(__('Ce projet n\'existe pas ou vous n\'y avez pas accès.'));
        $this->redirect(['controller'=>'Accueil', 'action'=>'index']);
    }
  }
}

  /**
  * Affiche les membres d'un projet (le propriétaire est considéré comme un membre et est donc aussi affiché).
  *
  * Fonction appelée au clic sur "Gérer les membres" dans le index.ctp du controller des Taches.
  *
  * La fonction vérifie si l'utilisateur a accès au projet à l'id donné en argument.
  * Si l'utilisateur n'y a pas accès, la fonction le redirige vers l'accueil.
  *
  * Paramètres : $id correspond à l'idProjet.
  * Retour : aucun.
  *
  * Redirection (si l'utilisateur n'a pas accès au projet): index de Accueil.
  *
  * @author POP Diana
  */
    public function index($id){
      $this->autorisation($id);
      $this->loadComponent('Paginator');
      $session = $this->request->getSession();
      $membres = $this->Paginator->paginate($this->Membre->find()
          ->contain(['Utilisateur'])
          ->where(['idProjet' => $id]));
      $this->set(compact('membres', 'id'));
    }



    /**
    * Ajoute un membre dans le projet.
    *
    * Fonction appelée au clic sur "Inviter" dans le index.ctp de ce controller.
    *
    * La fonction vérifie avant l'ajout :
    *       - Si l'utilisateur n'existe pas
    *       - Si l'utilisateur est pas déjà membre de cette liste
    *       - Si l'utilisateur est propriétaire du projet.
    *
    * Si l'un de ces critères est vrai, alors le membre n'est pas ajouté dans le projet.
    *
    * Paramètres : $id correspond à l'idProjet (les autres informations nécessaires viennent d'un POST).
    * Retour : redirection.
    * Redirection : index de ce controller.
    *
    * @author POP Diana
    */
    public function add($id){
      $this->autorisation($id);
      if ($this->request->is('post')){

      // Est-ce que l'utilisateur demandé existe ?
          $utilisateurs = TableRegistry::get('Utilisateur');
          $query = $utilisateurs->find()
              ->select(['idUtilisateur'])
              ->where(['pseudo' => $this->request->getData()['recherche_utilisateurs']])
              ->first();
          $id_utilisateur = $query['idUtilisateur'];

        if ($id_utilisateur===null){
          $this->Flash->error(__('Ce membre n\'existe pas.'));
          return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id]);
        }

        // Est-ce que l'utilisateur est propriétaire du projet ?
        $session = $this->request->getSession(); // Le check Session est vrai car on est passés par index de ce même controller
        if ($id_utilisateur===$session->read('Auth.User.idUtilisateur')){
          $this->Flash->error(__('Vous êtes le propriétaire de ce projet.'));
          return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id]);
        }

        // Est-ce que l'utilisateur demandé est déjà dans le projet ?
        $count = $this->Membre->find()->where(['idUtilisateur'=>$id_utilisateur, 'idProjet'=>$id])->count();
        if ($count>0){
          $this->Flash->error(__('Ce membre est déjà dans le projet.'));
          return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id]);
        }

        // Bienvenue au nouveau membre dans le projet !
        $membre = $this->Membre->newEntity();

        $membre->idProjet= $id;
        $membre->idUtilisateur= $id_utilisateur;

        if ($this->Membre->save($membre)) {
          $this->Flash->success(__('Le membre a été ajouté à la liste.'));

          return $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id]);
        }
        $this->Flash->error(__('Impossible d\'ajouter ce membre.'));
      } // fin if post
    }

    /**
    * Supprime un membre du projet.
    *
    * Fonction appelée au clic sur "Oui" du modal apparaissant après clic sur le bouton "Supprimer" du index.ctp de ce controller.
    *
    * La fonction vérifie avant l'ajout :
    *       - Si l'utilisateur est propriétaire du projet .
    *
    * Si ce critère est vrai, alors le membre n'est pas supprimé du projet.
    *
    * Paramètres : $id_utilisateur correspond à l'idUtilisateur et $id_projet correspond à l'idProjet.
    * Retour : redirections.
    * Redirection : index de ce controller.
    *
    * @author POP Diana
    */
    public function delete($id_utilisateur, $id_projet){
      $this->autorisation($id_projet);
      // Comme session ne marche pas, on va aller chercher l'idPropriétaire du projet.
      $projets = TableRegistry::get('Projet');
      $projet = $projets->find()->where(['idProjet'=>$id_projet])->first();
      $id_proprio = $projet->idProprietaire;

      // Si l'utilisateur sélectionné est le propriétaire du projet, il ne peut pas se supprimer
      if ($id_utilisateur==$id_proprio){
        $this->Flash->set('Vous êtes propriétaire de ce projet.', ['element' => 'error']);
        // Ne croyez pas StackOverflow, cette ligne est nécessaire
        $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id_projet]);

      // Si l'utilisateur sélectionné n'en est pas le propriétaire, il supprime
      }else{
        $taches = TableRegistry::get('Tache');
        $tachesResponsable = $taches->find()->where(['idProjet'=>$projet->idProjet]);

        debug($tachesResponsable);

        $membre = $this->Membre->find()->where(['idUtilisateur'=>$id_utilisateur, 'idProjet'=>$id_projet])->first();
        $success = $this->Membre->delete($membre);
        $this->Flash->set('Le membre a été supprimé du projet.', ['element' => 'success']);
        $this->redirect(['controller'=>'Membre', 'action'=> 'index', $id_projet]);
      }
    }

    public function edit($id_utilisateur, $id_projet){
      return $this->redirect(['action'=> 'index', $id_projet]);
    }

}

?>
