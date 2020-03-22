<?php

use Cake\ORM\TableRegistry;

/**
* Fonction qui permet d'envoyer des notifications
* @author Théo ROTON
* @param a_valider : booléen pour savoir si la notification est à voir ou à valider
* @param type : type de la notification ('Informative', 'Invitation', 'Suppression' ou 'Proprietaire')
* @param contenu : contenu de la notificaton
* @param idProjet : id du projet auquel est lié la notification
* @param idTache : id de la tâche à laquelle est liée la notification (peut être null)
* @param idExpediteur : id de l'utilisateur qui a émis la notification
* @param destinataires : id des utilisateurs destinataires de la fonction
*/
function envoyerNotification($a_valider, $type, $contenu, $idProjet, $idTache, $idExpediteur, $destinataires){
  // On récupère la table des notifications
  $notifications = TableRegistry::getTableLocator()->get('Notification');

  // On crée une nouvelle notification avec les informations en paramètres
  $notification = $notifications->newEntity();
  $notification->a_valider = $a_valider;
  $notification->type = $type;
  $notification->contenu = $contenu;
  $notification->date = date('Y-m-d'); // Date d'aujourd'hui
  $notification->idProjet = $idProjet;
  $notification->idTache = $idTache;
  $notification->idExpediteur = $idExpediteur;
  // On sauvegarde la notification
  $notifications->save($notification);
  // On récupère l'id de la notification qui vient d'être généré
  $idNot = $notification->idNotification;

  // On récupère la table de vue des notifications
  $vue_notifications = TableRegistry::getTableLocator()->get('VueNotification');

  // Pour chaque destinataire de la notification, on crée une vue notification
  foreach ($destinataires as $dest) {
    // On crée la vue notification
    $vue_not = $vue_notifications->newEntity();
    // On indique l'id du destinaire et de la notification
    $vue_not->idUtilisateur = $dest;
    $vue_not->idNotification = $idNot;
    // On sauvegarde la vue notification
    $vue_notifications->save($vue_not);
  }
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
