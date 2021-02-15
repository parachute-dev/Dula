Parachute.ns("Parachute.Utils.Helper");

Parachute.Utils.Helper = function() {};

/* Helper functions and such live here */
Parachute.Utils.setLocation = function (url){
    window.location.href = url;
}

/* There is a mismatch with how different browsers expect co-ordinates served to document.elementFromPoint() (i.e. - relative or absolute) - this function addresses this */
Parachute.Utils.elementFromPoint = function(x, y){
    var isRelative = true;
    if(!document.elementFromPoint) return null;

    var scrolled;
    if(scrolled = window.scrollY > 0){
        isRelative = (document.elementFromPoint(0, scrolled + window.innerHeight - 1) == null);
    }
    else if(scrolled = window.scrollX > 0){
        isRelative = (document.elementFromPoint(scrolled + window.innerWidth - 1, 0) == null);
    }

    if(!isRelative){
        x += window.scrollX;
        y += window.scrollY;
    }
    else{
        x = x - jQuery(document).scrollLeft();
        y = y - jQuery(document).scrollTop();
    }

    return document.elementFromPoint(x, y);
};

/* A general use method that sets up miscellaneous things not already covered in their own files, likely varies for each site. Called in app.js */
Parachute.Utils.pageInit = function(){
    // Your code goes here
    // e.g., - jQuery(".content-inner").fitVids();
};

console.info("Parachute initialised: utils.js");