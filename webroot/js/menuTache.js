$('.dropdownTache').hide();

$(".ligne").hover(function(){
    $('#dropdownTache' + this.id).show();
},function(){
    $('#dropdownTache' + this.id).hide();
});
