<?php
/**
 * @auth TABARY Mathieu, PALMIERI Adrien
 * @param $titre String : nom du champs à vérifier
 * @return bool retourne vrai si le titre correspond aux critères donné dans le cahier des chargess
 */
function verification_titre($titre){
    $res = false;
    // Vérification de la taille
    if(!(strlen($titre) < 1  || strlen($titre) > 128)){
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
    if(!(strlen($description) > 512)){
        $res = true;
    }

    return $res;
}
