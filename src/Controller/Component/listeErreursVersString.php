<?php

/**
 * Permet d'afficher les erreurs (présent dans le tableau)
 * Souvent c'est lors d'un update ou d'un insert de la bdd où l'on peut obtenir les erreurs du vérificator avec $objet->errors
 *
 * @param  $ArrayError Tableau des erreurs à afficher
 * @return String      Le message à afficher
 * @author Diana POP, Thibault CHONÉ
 */
function listeErreursVersString($ArrayError){
  if($ArrayError){
    $error_msg = [];
    foreach($ArrayError as $errors){
      if(is_array($errors)){
        foreach($errors as $error){
          $error_msg[]    =   $error;
        }
      }else{
        $error_msg[]    =   $errors;
      }
    }

    return $error_msg;
  }
}

?>
