<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;


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

    // Initialisation des tables
    $table_notifs_projets = TableRegistry::getTableLocator()->get('VueNotificationProjet');
    $table_notifs_taches = TableRegistry::getTableLocator()->get('VueNotificationTache');

    // Les notifications non-vues et non à valider deviennent vues lorsque l'utilisateur va voir ses notifs (et ne sont donc plus affichées en gras)
    // Nécessaire d'utiliser query()->update() plutôt que updateAll() car besoin d'un contain pour l'attribut 'a_valider'.
    $table_notifs_projets->query()->update()->set(['vue'=>1])->where(['idUtilisateur'=>$idUtilisateur])->execute();
    $table_notifs_taches->query()->update()->set(['vue'=>1])->where(['idUtilisateur'=>$idUtilisateur])->execute();

    // Récupération des notifications de projet
    $notifsProjet = $table_notifs_projets->find()->contain(['NotificationProjet'])->where(['idUtilisateur' => $idUtilisateur])->toArray();
    $notifsTache = $table_notifs_taches->find()->contain(['NotificationTache'])->where(['idUtilisateur' => $idUtilisateur])->toArray();

    // On merge en une seule array les résultats des deux requêtes.
    $notifs = array_merge($notifsProjet, $notifsTache);

    // On trie l'array résultante. Le tri est déjà sur la date, puis sur si la notification est à valider.
    $notifs = Hash::sort($notifs, '{n}.une_notification.Date','asc');
    $notifs = Hash::sort($notifs, '{n}.une_notification.a_valider', 'desc');

    // Donne aux ctp les variables nécessaires
    $this->set(compact('notifs'));

  }


}
?>
