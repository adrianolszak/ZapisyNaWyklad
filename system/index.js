$(function(){

var mainMenu = $('#boopa-nav-menu');


$(".boopa-nav-profile").on("click", function(){
  $(".boopa-nav-profile").toggleClass("active");
});
$("#boopa-nav-toggle").on("click", function(){
  toggleMainMenu();
});

function openSecondaryMenu(){
  mainMenu.addClass("inactive");
  $(".boopa-nav-profile").removeClass("active");
}
function openMainMenu(){
  mainMenu.removeClass('inactive');
  $(".boopa-nav-profile").removeClass("active");
}
function toggleSecondaryMenu(){
  mainMenu.addClass("inactive");
  $(".boopa-nav-profile").removeClass("active");
}
function toggleMainMenu(){
  mainMenu.toggleClass('inactive');
  $(".boopa-nav-profile").removeClass("active");
}
function closeMenus(){
  mainMenu.addClass("inactive");
  $(".boopa-nav-profile").removeClass("active");
}

});