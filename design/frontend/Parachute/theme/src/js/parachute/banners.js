Parachute.ns("Parachute.UI.Banners");
Parachute.ns("Parachute.UI.Banners.Helper");
Parachute.ns("Parachute.UI.Banners.Utils");

Parachute.UI.Banners = function() {};
Parachute.UI.Banners.Helper = function() {};
Parachute.UI.Banners.Utils = function() {};

/* All our lovely banner related JavaScript goes here! */
jQuery(document).ready( function(){
    jQuery('.banner-carousel.active .banner-list').each( function(){
        var slickSlider = jQuery(this),
        slides_to_show = 1,
        has_arrows = true,
        has_dots = false;
        
        /* By doing things this way we can configure loads of things just by checking for classes! */
        if(slickSlider.hasClass('control-type-arrows')){
          has_arrows = true;
          has_dots = false;
        }
        else if(slickSlider.hasClass('control-type-dots')){
          has_arrows = false;
          has_dots = true;
        }
    
        slickSlider.on('init', function (event, slick) {
          /* If we've got <video> elements we have to manually play them as autoplay is interrupted and does not re-init after slick rebuilds the markup */
          var sliderVideos = jQuery(slickSlider).find('.banner .video-container video');
    
          if(sliderVideos.length > 0){
            jQuery(sliderVideos).each(function (index) {
              sliderVideos[index].play();
            });
          }
        });
    
        slickSlider.on('breakpoint', function (event, slick) {
          /* If we've got <video> elements we have to manually play them as autoplay is interrupted and does not re-init after slick rebuilds the markup */
          var sliderVideos = jQuery(slickSlider).find('.banner .video-container video');
    
          if (sliderVideos.length > 0) {
            jQuery(sliderVideos).each(function (index) {
              sliderVideos[index].play();
            });
          }
        });
        
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

console.info("Parachute initialised: banners.js");