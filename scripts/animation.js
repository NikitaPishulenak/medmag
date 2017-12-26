$(document).ready(function() {
   var animDuration = 700;
   $('.intro').animate({
                   left: '0%'
                   }, animDuration);
   
   
    $('a').live("click", function(){
       var href = $(this).attr("href");
      var arrayHref=href.split('/');
       if ((arrayHref.length==6)&&(arrayHref[4].length==32)&&(arrayHref[4].indexOf("article")==-1)) return true;
       $('.intro').animate({
       left: '-110%'
       }, animDuration);               
        setTimeout(function () {
       window.location = href;
       }, animDuration);
    return false;
    });
});
