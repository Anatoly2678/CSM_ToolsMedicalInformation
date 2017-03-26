$(document).ready(function(){


$('.fancybox').fancybox();

$('.trigger').click(function(){
      $("nav").animate({right: 0}, 800);
  });
  $('nav .esc').click(function(){
      $("nav").animate({right: -320}, 800);
  });
  $('nav ul a').click(function(){
      $("nav").css('right', -320);
  });


$('nav ul a').on('click', function (e) {
    e.preventDefault();
    var id = $(e.currentTarget).attr('href');
    var $dest = $(id);
    $('html,body').animate({
      scrollTop: ($dest.offset().top)
    }, 1000);
  });







});