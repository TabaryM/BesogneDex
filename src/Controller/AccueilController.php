<?php
namespace App\Controller;

use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class AccueilController extends AppController
{

  /**
  * Index de la page d'accueil, cherche les 10ères tâches prioritaire et les liste dans $tachesPrioritaires
  * @author Thibault Choné
  */
  public function index()
  {
    $session = $this->request->getSession();
    if ($session->check('Auth.User.idUtilisateur')) {
      $idUtilisateur = $session->read('Auth.User.idUtilisateur');

      $dateDans7Jours = Time::now();
      $dateDans7Jours->modify('+7 days'); //On ajoute 7 jours à la date du jour


      /*SELECT tache.titre, dateFin
      FROM projet JOIN Tache USING(idProjet)
      Where idProprietaire == $idUtilisateur and dateFin < $dateDans7Jours and dateArchivage IS NULL and finie == 0*/

      //On recherche toute les tâches non finie des projets qui ont une date qui se finit dans moins de 7 jours, dans un projet non archivé
      $tachesResponsable = TableRegistry::getTableLocator()
        ->get('Tache')->find()
        ->select(['titre', 'Projet.dateFin'])
        ->contain('Projet')
        ->distinct()
        ->where(['idResponsable' => $idUtilisateur, 'Projet.dateFin <' => $dateDans7Jours, function ($exp, $q) { return $exp->isNull('dateArchivage'); }, 'finie' => 0])
        ->limit(10)
        ->toArray()
        ;

      //C'est pas ultra propre mais c'est mieux pour le front,
      //Ici on refait le tableau avec de bon indices (titre, dateFin) au lieu de (titre, leProjet->dateFin)
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
