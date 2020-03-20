<?php

/**
 * Empêche les injections HTML et SQL
 *
 * @param $texte String source a traiter
 * @return string texte nettoyé
 * @author PALMIERI Adrien
 */
function nettoyerTexte($texte) {
    $texte = filter_var($texte, FILTER_SANITIZE_STRING);
    $texte = htmlspecialchars($texte);
    //TODO fix that
    $texte = str_replace("&#39;","'", $texte);
    return $texte;
}

/**
 * Vérifie et nettoie les titres de projet ou de taches
 * @author TABARY Mathieu, PALMIERI Adrien
 * @param $titre String : nom du champs à vérifier
 * @return bool retourne vrai si le titre correspond aux critères donné dans le cahier des chargess
 */
function verificationTitre($titre){
    $titre = nettoyerTexte($titre);
    // Par défaut le titre contient une erreur
    $res = false;
    // Si le titre correspond au critères du cahier des charges, on retire l'erreur
    if(strlen($titre) >= 1  && strlen($titre) <= 128){
        $res = true;
    }

    return $res;
}

/**
 * Vérifie et nettoie les description de projet ou de taches
 * @author TABARY Mathieu, PALMIERI Adrien
 * @param $description String : nom du champs à vérifier
 * @return bool retourne vrai si la description correspond aux critères donné dans le cahier des chargess
 */
function verificationDescription($description){
    $description = nettoyerTexte($description);
    // Par défaut la description contient une erreur
    $res = false;
    // Si la description correspond au cahier des charges, on retire l'erreur
    if(strlen($description) <= 512){
        $res = true;
    }

    return $res;
}

/**
 * Vérifie que la date de début est antérieur à la date de fin, que la date de fin existe
 * @author TABARY Mathieu
 * @param $dateDebut array : Date de début du projet
 * @param $dateFin array : Date de fin du projet
 * @return bool retourne vrai si la date de début est plus ancienne que la date de fin. Ou si la date de fin n'est pas définie
 */
function verificationDates($dateDebut, $dateFin){
    // Si la date de fin n'est pas définie tout va bien
    if($dateFin == null){
        return true;
    }

    $res = false;
    // On convertis les dates en format comparable facilement
    $dateDebut = strtotime(implode($dateDebut));
    $dateFin = strtotime(implode($dateFin));

    // Si la date de début est antérieure à la date de fin tout va bien
    if($dateDebut <= $dateFin){
        $res = true;
    }
    return $res;
}

/**
 * Si la date passée en paramètre est vide, la supprime
 * @param $date array(date) : La date à nettoyer
 * @return string : Retourne null si la date est incomplète. Retourne la date sans modification si elle est complète.
 * @author TABARY Mathieu
 */
function nettoyageDate($date){
    $cpt = 0;

    if($date['year'] == '') $cpt++;
    if($date['month'] == '') $cpt++;
    if($date['day'] == '') $cpt++;

    switch ($cpt){
        case 0:
            $res = "bien fait";
            break;
        case 1:
        case 2:
            $res = "mal fait";
            break;
        case 3:
            $res = "pas fait";
            break;
        default:
            $res = "Erreur";
    }

    return $res;
}

/**
 * Vérifie si la date passée en paramètre est antérieur à la date du jour
 * @param $dateFin array(date) la date à vérifier
 * @return bool : Retourne vrai si la date est antérieur à la date du jour (ou si la date de fin n'est pas définie), faux sinon.
 * @author TABARY Mathieu
 */
function verificationDateFin($dateFin){
    // Si la date de fin n'est pas définie tout va bien
    if($dateFin == null){
        return true;
    }
    // Par défaut la date de fin contient une erreur
    $res = false;
    $dateDuJour = array(
        'year' => getdate()['year'],
        'month' => getdate()['mon'],
        'day' => getdate()['mday']
    );
    $dateDuJour = strtotime(implode($dateDuJour));
    $dateFin = strtotime(implode($dateFin));

    // Si la date de fin ultérieure à la date du jour tout va bien
    if($dateFin > $dateDuJour){
        $res = true;
    }
    return $res;
}
