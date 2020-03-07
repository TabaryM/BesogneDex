<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class AccueilController extends AppController
{


  public function index()
  {
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $idUtilisateur = $session->read('Auth.User.idUtilisateur');
      $today = Time::now();
      $today->modify('+7 days');


      /*SELECT tache.titre, dateFin
      FROM projet JOIN Tache USING(idProjet)
      Where idProprietaire == $idUtilisateur and dateFin < $today*/

      $tachesResponsable = TableRegistry::getTableLocator()
        ->get('Tache')->find()
        ->select(['titre', 'Projet.dateFin'])
        ->contain('Projet')
        ->distinct()
        ->where(['idResponsable' => $idUtilisateur, 'Projet.dateFin <' => $today])
        ->limit(10)
        ->toArray()
        ;

      $tachesPrioritaires = array();
      foreach($tachesResponsable as $tache) {
        array_push($tachesPrioritaires, ['titre' => $tache['titre'], 'dateFin' => $tache['leProjet']->dateFin]);
      }
      $this->set(compact('tachesPrioritaires'));
    }
  }

  public function unauthorized(){
    $this->Flash->error(__('Vous n\'avez pas accès à cette page.'));

    return $this->redirect(['action'=> 'index']);
  }

}
?>
