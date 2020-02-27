function che(idTache) {
  jQuery("#Tache" + idTache).submit();

  var xhttp = new XMLHttpRequest();
  xhttp.open("GET", "ajax_info.txt", true);
  xhttp.send();
}
