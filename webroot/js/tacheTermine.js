$('.checkFait').change(function() {
  var id = $(this).val()
  if (this.checked) {
    $.get("/tache/changerEtat/" + id + "/1", function(response) {
      console.log("Modification effectuée ! Checked")
    });
  } else {
    $.get("/tache/changerEtat/" + id + "/0", function(response) {
      console.log("Modification effectuée ! Not Checked")
    });
  }
});
