<?php

use Cake\ORM\TableRegistry;

/**
 * Ajoute un utilisateur à un projet
 * @param  $idProjet int      id du projet
 * @param  $idUtilisateur int id du membre à ajouter au projet
 * @author Clément Colné
 */
function ajouterMembre($idProjet, $idUtilisateur) {
    $membres = TableRegistry::getTableLocator()->get('Membre');
    $membre = $membres->newEntity();

    $membre->set('idUtilisateur', $idUtilisateur);
    $membre->set('idProjet', $idProjet);
    $membres->save($membre);
}
