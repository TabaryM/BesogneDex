$(document).ready(function() {
  $('li.active').removeClass('active');
  $('a[href="' + location.pathname + '"]').closest('li').addClass('active');
  $('active').css("font-size", 20 + "px");
});
