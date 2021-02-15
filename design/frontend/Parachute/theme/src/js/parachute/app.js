

// Scroll to Link
jQuery(function () {
    jQuery('a[href*="#"]:not([href="#"])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = jQuery(this.hash);
            target = target.length ? target : jQuery('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                jQuery('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });
});

  jQuery(document).ready(function(event){

  jQuery(".banner-carousel").slick({
      infinite: true,
      slidesToShow: 1,
      dots: true,
      speed: 600,
      fade: true,
      cssEase: 'linear',
      appendDots: jQuery("#banner-paging")
    });

   // Toggle the list on click
    jQuery(".list-container").click(function (event) {
        jQuery(this).toggleClass("active");
    });


    // Toggle the list on focus (when a user tabs to it)
    jQuery(".list-container input").focus(function (event) {
        jQuery(this).parent().toggleClass("active");
    });

    // Deal with the aftermath of making ... a selection in the list
    jQuery(".list-container ul li").click(function (event) {

        // Get the value for SmartCentre and hold it in a hidden field
        var inputHiddenValue = jQuery(this).data("value");

        // Get the human (nice) value
        var inputValue = jQuery(this).text();

        // Bind to the inputs
        jQuery(this).closest(".list-container").find("input[type=text]").val(inputValue).trigger('change');

        jQuery(this).closest(".list-container").find("input[type=hidden]").val(inputHiddenValue).trigger('change');
        jQuery(this).closest(".list-container").addClass("selected");

    /*// Focus on the next input group
    inputGroup = jQuery(this).closest(".input-group");
    nextInputGroup = jQuery(inputGroup).next();
    jQuery(nextInputGroup).find("input").focus();*/


    });



    jQuery(".loader").click(function (event) {

        var loader = this;
        jQuery(this).addClass("loading");
        jQuery("form").submit(function (e) {

            var url = jQuery(this).attr("action"); // the script where you handle the form input.

            jQuery.ajax({
                type: "POST",
                url: url,
                data: $("form").serialize(), // serializes the form's elements.
                success: function (data) {
                    jQuery(loader).removeClass("loading");
                    jQuery(loader).addClass("loaded");


                    jQuery("body").addClass("modal-visible");
                    var modal = jQuery(loader).data("modal");
                    var target = "#modal-" + modal;

                    jQuery(".modal-box").fadeIn();
                    jQuery(target).addClass("active");
                    jQuery(".modal-box").addClass("active");

                    window.dataLayer = window.dataLayer || [];
                    window.dataLayer.push({
                        event: "formSubmission",
                        formID: "Contact Us"
                    });                     
                }
            });

            e.preventDefault(); // avoid to execute the actual submit of the form.
        });

    });

        jQuery(".a-loading").removeClass("first-load");
    var Closed = false;

    jQuery('.hamburger').click(function () {
        if (Closed == true) {
            jQuery(this).removeClass('open');
            jQuery(this).addClass('closed');
            jQuery("body").toggleClass("mobile-open");
            Closed = false;
        } else {
            jQuery(this).removeClass('closed');
            jQuery(this).addClass('open');
            jQuery("body").toggleClass("mobile-open");

            Closed = true;
        }
    });

});