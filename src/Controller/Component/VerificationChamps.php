<?php
/**
 * @auth TABARY Mathieu, PALMIERI Adrien
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
 * @auth TABARY Mathieu, PALMIERI Adrien
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

function verification_dates($dateDebut, $dateFin){
    $res = false;
    // On convertis les dates en format comparable facilement
    $dateDebut = strtotime(implode($dateDebut));
    $dateFin = strtotime(implode($dateFin));

    // Si la date de début est infèrieur à la date de fin, on dit que les dates sont valides
    if($dateDebut <= $dateFin){
        $res = true;
    }
    return $res;
}
