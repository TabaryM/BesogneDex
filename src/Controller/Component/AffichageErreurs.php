<?php

/**
* Permet d'afficher les erreurs
* @author Diana POP, (Thibault CHONÉ)
* @param $ArrayError : Liste des erreurs à afficher (il est possible que cette variable contiennent également des tableaux d'erreurs)
*/
function affichage_erreurs($ArrayError){
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

    if(!empty($error_msg)){
      $this->Flash->error(
        __("Veuillez modifier ce(s) champs : ".implode("\n \r", $error_msg))
      );
    }
  }
}

?>
