$(document).ready(function() {
  $('.checkFait').change(function() {
    var id = $(this).val()
    if (this.checked) {
      $.get("/tache/changerEtat/" + id + "/1", null);
    } else {
      $.get("/tache/changerEtat/" + id + "/0", null);
    }
  });
});