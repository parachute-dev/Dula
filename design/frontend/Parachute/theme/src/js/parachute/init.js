/* Make it so - this is where you put those functions and methods into practice! */
jQuery(document).ready( function(){
    Parachute.Utils.pageInit(); // pageInit() - is in "functions.js" by default

    /* Hides content initially, remove once we've initialised */
    jQuery('.swup-loading').removeClass('initial-load');
});

jQuery(document).scroll( function(){

});

jQuery(document).resize( function(){

});

jQuery(document).mousemove(function(e) {
    window.mouseX = e.pageX;
    window.mouseY = e.pageY;
});
console.info("Parachute initialised: init.js");