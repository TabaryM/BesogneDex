<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;


class NotificationController extends AppController
{

  /**
  * Vérifie si l'utilisateur peut accéder aux notifications.
  * Si l'utilisateur n'est pas connecté, alors il ne peut pas y accéder.
  *
  * @return id de l'utilisateur s'il est connecté
  * Redirection vers la page précédente sinon.
  *
  * @author POP Diana, SOUSA RIBIERO Pedro
  */
  private function autorisation(){
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')){
      return $session->read('Auth.User.idUtilisateur');
    }else{
      $this->Flash->error(_('Vous devez être connecté pour accéder à vos notifications.'));
      $this->redirect($this->referer());
    }
  }

  /**
   * Récupère les notifications de l'utilisateur connecté (affiche une erreur flash si l'utilisateur n'est pas connecté)
   *
   * @author POP Diana, SOUSA RIBIERO Pedro
   */
  public function index(){
    $idUtilisateur= $this->autorisation();

    // Affichage des notifications de projet
    $table_notifs_projets = TableRegistry::getTableLocator()->get('VueNotificationProjet');
    $notifs = $table_notifs_projets->find()->contain(['NotificationProjet'])->where(['idUtilisateur' => $idUtilisateur])->order(['a_valider'=>'DESC', 'date'=> 'DESC'])->toArray();

    // Les notifcations non-vues et non à valider deviennent vues lorsque l'utilisateur va voir ses notifs
    $table_notifs_projets->updateAll(['vue'=>1], ['idUtilisateur'=>$idUtilisateur]);
    
    // Donne aux ctp les variables nécessaires
    $this->set(compact('notifs'));

  }


}
?>
