<?php
/**
 * @author TABARY Mathieu, PALMIERI Adrien
 * @param $titre String : nom du champs à vérifier
 * @return bool retourne vrai si le titre correspond aux critères donné dans le cahier des chargess
 */
function verification_titre($titre){
    $res = false;
    // Vérification de la taille
    if(strlen($titre) >= 1  && strlen($titre) <= 128){
        $res = true;
    }

    return $res;
}

/**
 * @author TABARY Mathieu, PALMIERI Adrien
 * @param $description String : nom du champs à vérifier
 * @return bool retourne vrai si la description correspond aux critères donné dans le cahier des chargess
 */
function verification_description($description){
    $res = false;
    // Vérification de la taille
    if(strlen($description) <= 500){
        $res = true;
    }

    return $res;
}

/**
 * @author TABARY Mathieu
 * @param $dateDebut array : Date de début du projet
 * @param $dateFin array : Date de fin du projet
 * @return bool retourne vrai si la date de début est plus ancienne que la date de fin
 */
function verification_dates($dateDebut, $dateFin){
    $res = false;
    // On convertis les dates en format comparable facilement
    $dateDebut = strtotime(implode($dateDebut));
    $dateFin = strtotime(implode($dateFin));
    $dateDuJour = strtotime(implode(getdate()));

    // Si la date de début est inferieur à la date de fin
    if($dateDebut <= $dateFin && $dateFin > $dateDuJour){
        $res = true;
    }
    return $res;
}
