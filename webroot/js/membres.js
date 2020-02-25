var jeSuisIdSelectionne = null;

function afficherGris(id){
  jQuery('.ligne_membre').css('background', 'white');
  jQuery('#'+id).css('background', 'LightGrey');
  jeSuisIdSelectionne = id;
}
