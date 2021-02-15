jQuery(document).ready(function(event){

  function showModals(thisInstance){
    jQuery("body").addClass("modal-visible");
    jQuery(thisInstance).addClass("active");
    jQuery(".para-modal-box").addClass("active");

  }

  jQuery(".modal-trigger").click(function(event) {
    var thisInstance = this;
    event.preventDefault();
    showModals(thisInstance); 
  });

  function closeModals(){
    jQuery("body").removeClass("modal-visible");
    jQuery(".para-modal-content-box").removeClass("active");
    jQuery(".para-modal-box").removeClass("active");
  }

  document.getElementById('para-modal-calendar').addEventListener('click', function (e) {
    alert();
    closeModals();
  });

  showModals("#para-modal-calendar"); 
  
});