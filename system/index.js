$(function(){

var mainMenu = $('#boopa-nav-menu');


$(".boopa-nav-profile").on("click", function(){
  //open profile submenu
  $(".boopa-nav-profile").toggleClass("active");
});
$("#boopa-nav-toggle").on("click", function(){
  toggleMainMenu();
});

function openSecondaryMenu(){
  //$("#boopa-nav-toggle").removeClass('active');
  mainMenu.addClass("inactive");
  $(".boopa-nav-profile").removeClass("active");
  container.removeClass("expand-right");
}
function openMainMenu(){
  //$("#boopa-nav-toggle").addClass('active');
  mainMenu.removeClass('inactive');
  $(".boopa-nav-profile").removeClass("active");
  container.addClass("expand-right");
}
function toggleSecondaryMenu(){
  //$("#boopa-nav-toggle").removeClass('active');
  mainMenu.addClass("inactive");
  $(".boopa-nav-profile").removeClass("active");
  container.removeClass("expand-right");
}
function toggleMainMenu(){
  //$("#boopa-nav-toggle").toggleClass('active');
  mainMenu.toggleClass('inactive');
  $(".boopa-nav-profile").removeClass("active");
  container.toggleClass("expand-right");
}
function closeMenus(){
  //$("#boopa-nav-toggle").removeClass('active');
  mainMenu.addClass("inactive");
  container.removeClass("expand-right");
  $(".boopa-nav-profile").removeClass("active");
}

});