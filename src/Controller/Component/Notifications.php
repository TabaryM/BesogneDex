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
  if (sizeof($destinataires) > 0){
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
}
