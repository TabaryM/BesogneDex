var jeSuisIdSelectionne = null;

function afficherGris(id){
  jQuery('.ligne_membre').css('background', 'white');
  jQuery('#'+id).css('background', 'LightGrey');
  jeSuisIdSelectionne = id;
}

function supprimer(){
  if (jeSuisIdSelectionne == null){
    return;
  }
  var jsonString = JSON.stringify(jeSuisIdSelectionne);

  jQuery.ajax({
   dataType: 'html',
   type: 'POST',
   url:"<?= Router::url(array('controller'=>'Membre', 'action'=> 'delete')); ?>" ,
   data: {data : jsonString},
   success: alert("success")
  });
}
