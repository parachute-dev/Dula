Parachute.ns("Parachute.UI.Dropdown");
Parachute.ns("Parachute.UI.Dropdown.Helper");
Parachute.ns("Parachute.UI.Dropdown.Utils");

Parachute.UI.Dropdown = function() {};
Parachute.UI.Dropdown.Helper = function() {};
Parachute.UI.Dropdown.Utils = function() {};

/* Dropdown code goes here */
Parachute.UI.Dropdown.dropdownToggle = function(dropdown){
    /* We only want these active on mobile */
    if( 
      jQuery(dropdown).hasClass('course-details-list-title') ||
      jQuery(dropdown).hasClass('sub-content-nav-list-title') &&
      jQuery(window).width() > 767
      )
    {
      return false;
    }

    var dropdown = jQuery(dropdown).parents().eq(1);
    var btn = jQuery(dropdown);
    var content = jQuery(dropdown).parent().siblings('.dropdown-content');

    if(btn.hasClass('active')){
      btn.removeClass('active');
      content.slideUp(100);
      dropdown.removeClass('active');
    }
    else{
      dropdown.addClass('active');
      btn.addClass('active');
      content.slideDown(100);
    }

    if(dropdown.hasClass('mega-dropdown-block-list')) megaPulldownOverflowScrollToggle();
};

jQuery(document).ready( function(){
    jQuery('.dropdown-menu-control').click( function(event){
        event.preventDefault();
        event.stopImmediatePropagation();

        var dropdown = jQuery(this);
        dropdownToggle(dropdown);
    });
});

console.info("Parachute initialised: dropdown.js");