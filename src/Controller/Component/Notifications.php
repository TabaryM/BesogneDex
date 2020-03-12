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
