var jeSuisIdSelectionne = null;

/**
 * Affiche la ligne en gris quand on clique dessus
 * @param  id l'id de la ligne sélectionnée.
 * @author Pop Diana
 */
function afficherGris(id){
  jQuery('.ligne_membre').css('background', 'white');
  jQuery('#'+id).css('background', 'LightGrey');
  jeSuisIdSelectionne = id;
  $('.bouton_supprimer_membre').prop('disabled', '');
  $('.changer_proprietaire').prop('disabled', '');
}

/**
 * Supprime un membre du projet
 * @param  id_projet id d'un projet
 * @author Pop Diana
 */
function supprimer(id_projet){
  if (jeSuisIdSelectionne == null){
    return;
  }

  jQuery.get("/BesogneDex/membre/delete/"+jeSuisIdSelectionne+ "/" + id_projet,
            function(){
                document.location.reload(true);
                });
}

/**
 * Change le propriétaire d'un projet
 * @param  idProjet id d'un projet
 * @author Clément Colné
 */
function changerProprietaire(idProjet){
  if (jeSuisIdSelectionne == null){
    // TODO : mettre le bouton en désactivé si aucun membre sélectionné
    return;
  }
    jQuery('#bouton_changer_proprietaire').prop('enabled', false);
    jQuery.get("/BesogneDex/projet/changerProprietaire/" + jeSuisIdSelectionne + "/" + idProjet,
              function(){
                  document.location= "/BesogneDex/Projet/index";
                });
}
