var jeSuisIdSelectionne = null;

function afficherGris(id){
  jQuery('.ligne_membre').css('background', 'white');
  jQuery('#'+id).css('background', 'LightGrey');
  jeSuisIdSelectionne = id;
}

function supprimer(id_projet){
  if (jeSuisIdSelectionne == null){
    return;
  }
    jQuery('#bouton_supprimer_membre').prop('enabled', false);
    jQuery.get("/BesogneDex/membre/delete/"+jeSuisIdSelectionne+ "/" + id_projet,
              function(){
                  document.location.reload(true);
                  jQuery('#bouton_supprimer_membre').prop('enabled', true);
                  });
  }
