Parachute.ns("Parachute.UI.Content");
Parachute.ns("Parachute.UI.Content.Helper");
Parachute.ns("Parachute.UI.Content.Utils");

Parachute.UI.Content = function() {};
Parachute.UI.Content.Helper = function() {};
Parachute.UI.Content.Utils = function() {};

/* JavaScript relating to our content-listings blocks should live here */
jQuery(document).ready( function(){
    jQuery('.content-listings-container.active .content-listings').each( function(){
        var slickSlider = jQuery(this),
        slides_to_show = 2,
        has_arrows = true,
        has_dots = false;
    
        /* Class driven config */
        if(slickSlider.hasClass('full-image')){
          slides_to_show = 3;
        }
        else if(slickSlider.hasClass('colour-block')){
          slides_to_show = 4;
        }
        else if(slickSlider.hasClass('popout-tab')){
          slides_to_show = 4;
        }
        else if(slickSlider.hasClass('full-image')){
          slides_to_show = 4;
        }
        else if(slickSlider.hasClass('col-1')){
          slides_to_show = 1;
        }
        else if(slickSlider.hasClass('col-2')){
          slides_to_show = 2;
        }
        else if(slickSlider.hasClass('col-3')){
          slides_to_show = 3;
        }
        else if(slickSlider.hasClass('col-4')){
          slides_to_show = 4;
        }
    
        if(slickSlider.hasClass('control-type-arrows')){
          has_arrows = true;
          has_dots = false;
        }
        else if(slickSlider.hasClass('control-type-dots')){
          has_arrows = false;
          has_dots = true;
        }
    
          /* 
          * We don't want any <dt> elements to be included in the slider.
          * This can cause sizing issues or have a blank slide at the beginning.
          * - Ross
          */
          slickSlider.children('dt').remove();
    
          slickSlider.not('.slick-initialized').slick({
            arrows: has_arrows,
            appendArrows: jQuery(slickSlider).parent(),
            dots: has_dots,
            infinite: true,
            draggable: false,
            speed: 800,
            autoplay: true,
            autoplaySpeed: 8000,
            slidesToShow: slides_to_show,
            slidesToScroll: 1,
            accessibility: true,
            responsive: [
            {
              breakpoint: 767,
              settings: {
                slidesToShow: 1
              }
            }
            ],
            prevArrow:'<button class="slick-prev"><svg class="icon svg-canvas" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16.904 32" xml:space="preserve"><use xlink:href="#arrow-line-left"></use></svg></button>',
            nextArrow:'<button class="slick-next"><svg class="icon svg-canvas" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 16.904 32" xml:space="preserve"><use xlink:href="#arrow-line-right"></use></svg></button>',
          });
    }); 
});

console.info("Parachute initialised: content.js");