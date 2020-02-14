<?php
function verification_titre($nom){
    $res = false;
    // Vérification de la taille
    if(!(strlen($nom) < 1  || strlen($nom) > 128)){
        $res = true;
    }

    return $res;
}

function verification_description($description){
    $res = false;
    // Vérification de la taille
    if(!(strlen($description) < 1  || strlen($description) > 512)){
        $res = true;
    }

    return $res;
}
