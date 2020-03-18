<?php

use Cake\ORM\TableRegistry;


function envoyerNotificationProjet($a_valider, $contenu, $idProjet, $idUtilisateur){
  //On récupère la table des notifications des projets
  $notifications = TableRegistry::getTableLocator()->get('Notification_projet');

  //On crée une nouvelle notification pour le projet courant
  $notification = $notifications->newEntity();
  $notification->a_valider = $a_valider;
  $notification->contenu = $contenu;
  $notification->idProjet = $idProjet;
  $notifications->save($notification);
  $idNot = $notification->idNotificationProjet;

  //On récupère la table de vue des notifications des projets
  $vue_notifications = TableRegistry::getTableLocator()->get('Vue_notification_projet');

  $vue_not = $vue_notifications->newEntity();
  $vue_not->idUtilisateur = $idUtilisateur;
  $vue_not->idNotifProjet = $idNot;
  $vue_notifications->save($vue_not);

}
/*
function envoyerNotificationProjetAUnUtilisateur($a_valider, $contenu, $idProjetOuTache, $idUtilisateur, $type){
  $idNotif = nouvelleNotification($a_valider, $contenu, $idProjetOuTache, $type);
  //On récupère la table de vue des notifications des projets
  if($type == 'tache'){
    $TableVueNotifications = TableRegistry::getTableLocator()->get('Vue_notification_tache');
  }else{
    $TableVueNotifications = TableRegistry::getTableLocator()->get('Vue_notification_projet');
  }
  miseAJourVuesProjet($idUtilisateur, $idNotif, $TableVueNotifications);
}

function envoyerNotificationProjetAuxMembres($a_valider, $contenu, $idProjet){
  $idNotif = nouvelleNotification($a_valider, $contenu, $idProjet);
  miseAJourVuesMembresProjet($idProjet, $idNotif);
}

function nouvelleNotification($a_valider, $contenu, $idProjet){
  //On récupère la table des notifications des projets
  $notifications = TableRegistry::getTableLocator()->get('Notification_projet');

  //On crée une nouvelle notification pour le projet courant
  $notification = $notifications->newEntity();
  $notification->a_valider = $a_valider;
  $notification->contenu = $contenu;
  $notification->idProjet = $idProjet;
  $notifications->save($notification);
  $idNot = $notification->idNotificationProjet;
  return $idNot;
}

function miseAJourVuesProjet($idUtilisateur, $idNotif, $TableVueNotifications){
  $NouvelleVueNotif = $TableVueNotifications->newEntity();
  $NouvelleVueNotif->idUtilisateur = $idUtilisateur;
  $NouvelleVueNotif->idNotifProjet = $idNotif;
  $TableVueNotifications->save($NouvelleVueNotif);
}


function miseAJourVuesMembresProjet($idProjet, $idNotif){

  //On récupère les membres du projet
  $membres = TableRegistry::getTableLocator()->get('Membre');
  $membres = $membres->find()->contain('Utilisateur')
                     ->where(['idProjet' => $idProjet]);


  $TableVueNotifications = TableRegistry::getTableLocator()->get('Vue_notification_projet');
  foreach ($membres as $m) {
    $idUtil = $m->un_utilisateur->idUtilisateur;

    miseAJourVuesMembresProjet($idUtil, $idNotif, $TableVueNotifications);
  }
}*/
