/* For tidiness, our event handlers and methods related to navigation go here */
Parachute.ns("Parachute.UI.Nav");
Parachute.ns("Parachute.UI.Nav.Helper");
Parachute.ns("Parachute.UI.Nav.Utils");
Parachute.ns("Parachute.UI.Nav.MegaPulldown");
Parachute.ns("Parachute.UI.Nav.Mobile");

Parachute.UI.Nav = function() {};
Parachute.UI.Nav.Helper = function() {};
Parachute.UI.Nav.Utils = function() {};
Parachute.UI.Nav.MegaPulldown = function() {};
Parachute.UI.Nav.Mobile = function() {};

Parachute.UI.Nav.Helper.stickyNav = function(){
    if(jQuery(window).width() < 767) return;

    var scroll_val = window.pageYOffset;
    var page_margin_top = jQuery("#surround").css("margin-top");
    var header_nav_h = jQuery("#header").height();
    var header_scroll_y2 = jQuery("#header").height() - (jQuery("#header").height() - 40); // Bottom

    if(scroll_val <= header_scroll_y2 + (parseInt(page_margin_top) - header_nav_h) && jQuery("#header").hasClass("sticky-nav")){
        jQuery("body, #main-nav-container").removeClass("sticky-nav");
    }
    else if(scroll_val >= header_scroll_y2){
        jQuery("body, #header").addClass("sticky-nav");
    }
    else{
        jQuery("body, #header").removeClass("sticky-nav");
    }
};

/* Little lists of controls that if necessary allow a user to navigate up and down the page to specified points */
Parachute.UI.Nav.Utils.verticalNavAnchorInit = function(){
    jQuery(".vertical-nav-anchor").each( function(index){
      /* Setup */
      if(
        typeof window.vertical_nav_anchors == 'undefined' ||
        typeof window.vertical_nav_anchors != 'undefined' && jQuery('.vertical-nav-anchors').length == 0
      ){
        window.vertical_nav_anchors = [];
        jQuery('#swup').prepend('<div class="vertical-nav-anchors"><dl class="vertical-nav-list no-bullet"><dt class="sr-only">Page Nav:</dt></dl></div>');
      }
  
      window.vertical_nav_anchors.push( jQuery(this) );
      jQuery(this).attr('id', 'vertical-nav-anchor-' + index);
      var title = jQuery(this).find('.title').length > 0 ? jQuery(this).find('.title') : 'Vertical Nav Point ' + index;
      jQuery('.vertical-nav-list').append('<dd id="vertical-nav-item-' + index + '" class="vertical-nav-item"><button class="button" title="' + title + '"><span class="sr-only">' + title + '</span></button></dd>');
    });
};

/* Accessibility fix to allow vertical scrolling on megadropdowns on screens that aren't too tall */
Parachute.UI.Nav.MegaPulldown.megaPulldownOverflowScrollToggle = function(){
    var megadropdown = jQuery('.mega-dropdown.active');
    if(megadropdown.length < 1) return;

    /* Calculate the overflow amount */
    var megadropdown_h = megadropdown.outerHeight();
    var megadropdown_offset_y = jQuery('#main-nav-container').hasClass('sticky-nav') == false ? megadropdown.offset().top : 0;
    var window_h = jQuery(window).height();
    var overflow_h = Math.ceil(megadropdown_h + megadropdown_offset_y - window_h);
    var megadropdown_scrollable_h = megadropdown_h - overflow_h;

    /* If our .mega-dropdown is hanging below the edge of the vertical viewport then limit it's  */
    if( overflow_h > 0 ){
        jQuery(megadropdown).addClass('overflow-y-scrollable');
        jQuery(megadropdown).find('.mega-dropdown-subnav').outerHeight(megadropdown_scrollable_h);
        jQuery(megadropdown).find('.mega-dropdown-blocks-container.active').outerHeight(megadropdown_scrollable_h);
    }
    else if(megadropdown.hasClass('overflow-y-scrollable')){
        megadropdown.removeClass('overflow-y-scrollable');
        jQuery(megadropdown).find('.mega-dropdown-subnav').outerHeight('auto');
        jQuery(megadropdown).find('.mega-dropdown-blocks-container.active').outerHeight('auto');
    }
};

Parachute.UI.Nav.Mobile.mobileNavController = function(event){
    var el = jQuery(event.currentTarget);
    var controlType = mobileNavHelperGetControlType(el);

    if(controlType != false){
        /* Depending on what's been clicked we'll take different actions */
        switch(controlType){
        case 'open':
        mobileNavActionOpen(el);
        break;

        case 'close':
        mobileNavActionClose();
        break;

        case 'forward':
        mobileNavActionForward(el);
        break;

        case 'back':
        mobileNavActionBack();
        break;

        default:
            return false;
        break;
        }
    }
};
  
Parachute.UI.Nav.Mobile.mobileNavHelperGetControlType = function(element){
    if(element.hasClass('mobile-nav-open')){
        return 'open';
    }
    else if(element.hasClass('mobile-nav-close')){
        return 'close';
    }
    else if(element.hasClass('mobile-nav-menu-item-link')){
        return 'forward';
    }
    else if(element.hasClass('mobile-nav-back')){
        return 'back';
    }
    else{
        return false;
    }
};
  
Parachute.UI.Nav.Mobile.mobileNavActionOpen = function(element){
    element.addClass('active');
    jQuery('#mobile-nav').addClass('active');
    jQuery('body').addClass('mobile-nav-active');
};

Parachute.UI.Nav.Mobile.mobileNavActionClose = function(element){
    window.mobile_root_nav = jQuery('.mobile-nav-menu-block-list.level-0');
    window.mobile_current_nav = window.mobile_root_nav;
    window.mobile_prev_active_nav = window.mobile_root_nav;
    window.mobile_nav_open = false;

    jQuery('.mobile-nav-menu-block-list').removeClass('active current');
    jQuery('#mobile-nav, .mobile-nav-open').removeClass('active');
    jQuery('body').removeClass('mobile-nav-active');
};
  
Parachute.UI.Nav.Mobile.mobileNavActionForward = function(element){
    if(element.hasClass('has-children') == false){
        var url = element.attr('href');
        window.setLocation(url);
        mobileNavActionClose();
    }
    else{
        /* Right, let's get our next nav menu! */
        var parentMenuItem = element.parent(), 
        nextNavBlock = parentMenuItem.children('.mobile-nav-menu-block-list'),
        prevNavBlock = window.mobile_prev_active_nav;
        window.mobile_prev_active_nav = (window.mobile_root_nav != parentMenuItem.parents().eq(3)) ? parentMenuItem.parents().eq(3) : window.mobile_prev_active_nav;
    /*
        console.log('prev:', window.mobile_prev_active_nav);
        console.log('current:', window.mobile_current_nav);
        console.log('next:', nextNavBlock);
        */     
        if(nextNavBlock.length > 0){
        /* Hide the previous navigation */
        window.mobile_prev_active_nav = window.mobile_current_nav;
        window.mobile_prev_active_nav.removeClass('current').addClass('active');
        window.mobile_prev_active_nav.find('a').removeClass('current').addClass('active');

        /* Show the new current nav block */
        window.mobile_current_nav = nextNavBlock;
        element.addClass('current');
        nextNavBlock.addClass('current');
        }
    }
};
  
Parachute.UI.Nav.Mobile.mobileNavActionBack = function(element){
    /* Add/remove classes */
    window.mobile_current_nav.removeClass('current');
    window.mobile_current_nav.find('a').removeClass('current');
    window.mobile_prev_active_nav.removeClass('active').addClass('current');
    window.mobile_prev_active_nav.find('a').removeClass('current').addClass('active');

    /* Assign our blocks to our helper vars */
    window.mobile_current_nav = window.mobile_prev_active_nav;
    if(window.mobile_current_nav != window.mobile_root_nav) window.mobile_prev_active_nav = window.mobile_current_nav.parents().eq(5);
};

jQuery(document).ready( function(){
    /* Init */
    window.dropdown_mousein_timeout_active = false;
    window.dropdown_mouseout_timeout_active = false;
    window.mobile_root_nav = jQuery('.mobile-nav-menu-block-list.level-0');
    window.mobile_current_nav = window.mobile_root_nav;
    window.mobile_prev_active_nav = window.mobile_root_nav;
    window.mobile_nav_open = false;
  
    jQuery('#main-nav > ul > li').hover(
      function(event){
        event.stopImmediatePropagation(); // Swup.js is causing our events to fire twice, which isn't twice as nice! So stop'it!
        if(window.dropdown_mouseout_timeout_active != false) return;
  
        window.dropdown_mousein_timeout = 
        window.setTimeout(function(){
          var target = event.target;
          var el = elementFromPoint(window.mouseX, window.mouseY);
          var nav = jQuery('#main-nav > ul');
          
          if(jQuery(target).hasClass('main-nav-item')){
            var dropdown = jQuery(target).find('.mega-dropdown');
          }
          else{
            var dropdown = jQuery(target).parent('.main-nav-item').find('.mega-dropdown');
          }
          var nav_item = jQuery(dropdown).parent().attr('id');
  
          /* If this is the first time hovering over the navigation or we have stopped hovering over the nav items previously then add the active class for dropdowns here */
              /*
              console.log(event, 'event');
              console.log(jQuery(target).parents('.main-nav-item'), 'target.parents');
              console.log(jQuery(el), 'el');
              console.log(jQuery(el).parents('.main-nav-item'), 'el.parents');
              console.log(jQuery(el).parents('.main-nav-item').length, 'length');
              console.log(jQuery(el).hasClass('main-nav-item'), 'hasClass');
              */
  
              if( 
                jQuery(el).parents('.main-nav-item').length > 0 ||
                jQuery(el).hasClass('main-nav-item')
                ){  
                /* Hide any active mega-pulldowns */
              jQuery('.mega-dropdown.active').not(dropdown).removeClass('active');
  
              jQuery(nav).addClass('active');
              if(jQuery(el).hasClass('main-nav-item')){jQuery(el).find('.mega-dropdown').addClass('active');}
              else{jQuery(el).parents('.main-nav-item').find('.mega-dropdown').addClass('active');}
            }
            
            /* For IE pre-EDGE */
            if(jQuery(target).parents('.main-nav-item').length > 0){
              jQuery(nav).addClass('active');
              jQuery(target).parent().find('.mega-dropdown').addClass('active');
            }
  
            megaPulldownOverflowScrollToggle();
            
            window.dropdown_mousein_timeout_active = false;
          }, 000);
      },
      function(event){
        if(window.dropdown_mouseout_timeout_active == true || dropdown_mousein_timeout_active == true) return;
  
        window.dropdown_mouseout_timeout = 
        window.setTimeout(function(){
          window.dropdown_mouseout_timeout_active = true;
  
          var el = document.elementFromPoint(window.mouseX - window.scrollX, window.mouseY - window.scrollY);
          // var el = elementFromPoint(window.mouseX, window.mouseY);
          /*
          console.log(el, 'orig-el');
          console.log(document.elementFromPoint(window.mouseX, window.mouseY), 'el-no-offset');
          console.log(elementFromPoint(window.mouseX, window.mouseY), 'newfunc');
          */
          var dropdown = jQuery('.mega-dropdown.active');
          var nav = jQuery('#main-nav > ul');
          var nav_item = jQuery(dropdown).parent().attr('id');
  
          /* Is the element currently hovered over not a child of our active nav item or the active dropdown? */
          if(
            jQuery(el).parents('#' + nav_item).length == 0 ||
            jQuery(el).attr('id') != jQuery(dropdown).attr('id')
            ){jQuery(dropdown).removeClass('active');}
            
            /* If the item currently being hovered over on mouseout is the child of a main nav item or one itself then show it */
          if(
            jQuery(el).parents('.main-nav-item').length > 0 ||
            jQuery(el).hasClass('main-nav-item')
            ){
            jQuery(nav).addClass('active');
          if(jQuery(el).hasClass('main-nav-item')){jQuery(el).find('.mega-dropdown').addClass('active');}
          else{jQuery(el).parents('.main-nav-item').find('.mega-dropdown').addClass('active');}
        }
          /*
          else if(jQuery(target).parents('.main-nav-item').length > 0){
              jQuery(nav).addClass('active');
              jQuery(el).parent().find('.mega-dropdown-menu').addClass('active');
          }
          */
          else{
            jQuery(nav).removeClass('active');
            jQuery('.mega-dropdown').removeClass('active'); /*******/
            window.dropdown_mouseout_timeout = false;
          }
          
          window.dropdown_mouseout_timeout_active = false;
        }, 00);
    });
  
    /* Touch gesture navigation stuff for tablets */
    window.active_tap_nav = false;
  
    jQuery('#main-nav > ul > li > a').on( 'tap', function(e){
      if(jQuery(this).parent().find('.mega-dropdown').length > 0) e.preventDefault();
  
      if(jQuery(this).hasClass('active')){
        return false;
      }
      else{
        jQuery('#main-nav > ul > li, #main-nav > ul > li > a, #main-nav > ul > li > .mega-dropdown').removeClass('active tablet-nav-active');
  
        jQuery(this).addClass('active tablet-nav-active');
        jQuery(this).parent().addClass('active tablet-nav-active');
        jQuery(this).parent().find('.mega-dropdown').addClass('active tablet-nav-active');
      }
    });

    // Show the first mega-dropdown-blocks by default on load
    jQuery('.mega-dropdown-inner-row').find('.mega-dropdown-subnav-list-item:first, .mega-dropdown-blocks-container:first').addClass('active');
    jQuery('.mega-dropdown-subnav-list-item a.mega-dropdown-subnav-control').hover( function(event){
        //event.preventDefault();

        var id = jQuery(this).parent().attr('id'), url = jQuery(this).attr('href');
        id = id.replace('mega-dropdown-subnav-list-item-', '');

        if( jQuery('#main-nav-dropdown-blocks-container-' + id).length > 0 ){
        jQuery('.mega-dropdown.active').removeClass('overflow-y-scrollable');
        jQuery('.mega-dropdown-subnav-list-item').removeClass('active');
        jQuery(this).parent().addClass('active');
        jQuery(this).parents().eq(4).find('.mega-dropdown-blocks-container').removeClass('active').outerHeight('auto');
        jQuery(this).parents().eq(4).find('#main-nav-dropdown-blocks-container-' + id).addClass('active');
        megaPulldownOverflowScrollToggle();
        }
        else{
            //  window.location = url;
        }
    });

    jQuery('.mega-dropdown button.mega-dropdown-close-button').on( 'tap', function(e){
        jQuery('#main-nav > ul > li, #main-nav > ul > li > a, #main-nav > ul > li > .mega-dropdown').removeClass('active tablet-nav-active');
        jQuery(this).removeClass('active tablet-nav-active');
    });

    // Show the first mega-dropdown-blocks by default on load
    jQuery('.mega-dropdown-inner-row').find('.mega-dropdown-subnav-list-item:first, .mega-dropdown-blocks-container:first').addClass('active');
    jQuery('.mega-dropdown-subnav-list-item a.mega-dropdown-subnav-control').hover( function(event){
        //event.preventDefault();

        var id = jQuery(this).parent().attr('id'), url = jQuery(this).attr('href');
        id = id.replace('mega-dropdown-subnav-list-item-', '');

        if( jQuery('#main-nav-dropdown-blocks-container-' + id).length > 0 ){
            jQuery('.mega-dropdown.active').removeClass('overflow-y-scrollable');
            jQuery('.mega-dropdown-subnav-list-item').removeClass('active');
            jQuery(this).parent().addClass('active');
            jQuery(this).parents().eq(4).find('.mega-dropdown-blocks-container').removeClass('active').outerHeight('auto');
            jQuery(this).parents().eq(4).find('#main-nav-dropdown-blocks-container-' + id).addClass('active');
            megaPulldownOverflowScrollToggle();
        }
        else{
            //  window.location = url;
        }
    });

  /* Mobile Navigation Toggle Button */
  jQuery('.mobile-nav-control, .mobile-nav-menu-item > a.mobile-nav-menu-item-link').click( function(event){
    event.preventDefault();
    mobileNavController(event);
  });

  /* Mobile Navigation Menus */
  jQuery('.mobile-nav-item.has-children > h3.mobile-nav-item-title > a, .mobile-nav-item.has-children > a').click( function(event){
    event.preventDefault();

    var eq_val = (jQuery(this).parent().hasClass('mobile-nav-item')) ? 0 : 1;

    /* Show our direct subnav siblings */
    if(jQuery(this).hasClass('active')){
      jQuery(this).removeClass('active');
      jQuery(this).parent().removeClass('active');
      jQuery(this).parents().eq(eq_val).children('ul.mobile-subnav').removeClass('active');
    }
    else{
      jQuery(this).addClass('active');
      jQuery(this).parent().addClass('active');
      jQuery(this).parents().eq(eq_val).addClass('active').children('ul.mobile-subnav').addClass('active');
    }
  });

  /* Vertical Nav Anchor Page Navigation */
  Parachute.UI.Nav.Utils.verticalNavAnchorInit();

  jQuery('.vertical-nav-item .button').click( function(event){
    event.preventDefault();
    var button = jQuery(this);
    var id = button.parent().attr('id');
    if(id == '') return false;
    id = id.replace('vertical-nav-item-', '');
    
    jQuery('.vertical-nav-list .vertical-nav-item.active').removeClass('active');
    button.parent().addClass('active');
    jQuery("html, body").animate({ scrollTop: (jQuery('#vertical-nav-anchor-' + id).offset().top) }, "slow");
  });
});

jQuery(window).resize( function(){
    var width = jQuery(this).width();
    Parachute.UI.Nav.MegaPulldown.megaPulldownOverflowScrollToggle();
});

console.info("Parachute initialised: nav.js");