
jQuery(document).ready(function(event){


function showModals(thisInstance){
    
   
           jQuery("body").addClass("modal-visible");
       var modal = jQuery(thisInstance).data("modal");
       var target = "#modal-" + modal;
   
       jQuery(".modal-box").fadeIn();
       jQuery(target).addClass("active");
       jQuery(".modal-box").addClass("active");
   
       var originContainer = "#" + jQuery(thisInstance).attr("id");
   
       jQuery(target).data("control",originContainer);
   
   
       if (jQuery(thisInstance).hasClass("push-forward")){
           jQuery(target).data("push-forward","true");
       }
       if (jQuery(thisInstance).hasClass("modal-enquire-trigger")){
           jQuery(target).data("enquire-forward","true");
       }
   }
   
   
   jQuery(".modal-trigger input").keydown(function(event){
     return false;
   });
   
   
   
   
   jQuery(".modal-trigger").click(function(event) {

console.log("modal triggered");
       var thisInstance = this;
       event.preventDefault();
       showModals(thisInstance); 
   });
   
   jQuery(".modal-trigger input").focus(function(event){
   
       var thisInstance = this.parentNode;
   
       event.preventDefault();
       showModals(thisInstance);
   
   });
   
   
   function closeModals(){
       jQuery("body").removeClass("modal-visible");
       jQuery('#background-overlay').hide();
       jQuery(".boxes").removeClass('show');
       jQuery('.popup-box').removeClass('show');
       jQuery(".modal-box").fadeOut();
       jQuery(".modal-box .modal-content-box").removeClass("active");
       jQuery(".modal-content-box").data("push-forward","false");
       jQuery(".modal-box").removeClass("active");
       jQuery(".modal-content-box").data("enquire-forward", "false");
       jQuery(".button.loader").removeClass("loading");
       jQuery(".button.loader").removeClass("loaded");

   
   
   
   }

   jQuery(".close-modal-propogate").on("click", function(event) {
    
        closeModals();
    });
    


});