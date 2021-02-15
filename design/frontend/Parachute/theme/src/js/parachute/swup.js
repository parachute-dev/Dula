Parachute.ns("Parachute.Vendor.Swup");
Parachute.ns("Parachute.Vendor.Swup.Helper");
Parachute.ns("Parachute.Vendor.Swup.Utils");

Parachute.Vendor.Swup = function() {};
Parachute.Vendor.Swup.Helper = function() {};
Parachute.Vendor.Swup.Utils = function() {};

/* This is where Swup code should go if you want dynamically pages! Depends on lottie.js if you want an animated overlay! */
Parachute.Vendor.Swup.swupInit = function(){
    var anim;
    var elem = document.getElementById('loading-page');
    var animData = {
        container: elem,
        renderer: 'svg',
        loop: true,
        autoplay: true,
        rendererSettings: {
            progressiveLoad: false,
        },
        path: '/wp-content/themes/parachute-boilerplate/assets/json/lottie-data.json'
    };
    anim = lottie.loadAnimation(animData);
    lottie.goToAndStop(37, true); // Go to the last frame on load, obviously different depending on the animation
  
    /* Swup (Fancy page loads) */
    var linkSelectorStr = ''; // Defines what kinds of anchor swup will allow to trigger dynamic loading
  
    /* 
    * Whilst similar, the regex for this has to be structured a wee bit differently than a CSS regex,
    * e.g. a[href]:not( *="/wp-admin" ) to exclude a link containing "/wp-admin" rather than the CSS selector regex which would be:
    * a[href*="/wp-admin"] - I may be wrong admittedly, but this appears to be the case!
    */
    var linkSelectorStrArr = [
    'a[href="/"]:not([data-no-swup])',
    'a[href^="/"]:not([data-no-swup])',
    'a[href^="http://rcs2.local"]:not([data-no-swup])',
    'a[href^="http://rcs.beta.parachute.digital"]:not([data-no-swup])',
    'a[href^="https://rcs.beta.parachute.digital"]:not([data-no-swup])',
    'a[href^="http://rcs.ac.uk"]:not([data-no-swup])',
    'a[href^="https://rcs.ac.uk"]:not([data-no-swup])',
    'a[href^="#"]:not([data-no-swup])',
    'a[xlink\\:href]'
    ];
  
    for(var i=0; i < linkSelectorStrArr.length; i++){
      if(i != 0) linkSelectorStr += ', ';
      linkSelectorStr += linkSelectorStrArr[i];
    }
  
    var swupOptions = {
      LINK_SELECTOR: linkSelectorStr,
      elements: [
      '#swup',
      '#header-support-row',
      '#main-nav-container',
      '#mobile-nav',
      '#wpadminbar'
      ],
      animationSelector: '[class^="swup-a-"]',
      cache: false,
      scroll: true,
      doScrollingRightAway: true,
      animateScroll: true,
      scrollFriction: .3,
      scrollAcceleration: .04,
      debugMode: true,
      support: true,
      disableIE: true
    };

    /* De-swup the links you don't want to load via a page "frame" */
    jQuery('#wpadminbar a, .post-edit-link').each( function(){
        jQuery(this).attr('data-no-swup', '');
    });
};

jQuery(document).ready( function(){
    document.addEventListener('swup:pageView', function () {
        window.mobile_root_nav = jQuery('.mobile-nav-menu-block-list.level-0');
        window.mobile_current_nav = window.mobile_root_nav;
        window.mobile_prev_active_nav = window.mobile_root_nav;
        window.mobile_nav_open = false;
        lottie.play();
        pageInit();
    }); // swup:pageView
    
    document.addEventListener('swup:clickLink', function(event){
        jQuery('.mega-dropdown.active').removeClass('active');
        anim.goToAndStop(0, false);
        anim.play();
    }); // swup:clickLink
});

console.info("Parachute initialised: swup.js");