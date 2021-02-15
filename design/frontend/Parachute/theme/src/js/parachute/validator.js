
jQuery(document).ready(function() {
  

//Custom Validation for Full Name
jQuery.validator.addMethod('fullname', function (value) { 
  return /^(([A-Za-z]+[\-\']?)*([A-Za-z]+)?\s)+([A-Za-z]+[\-\']?)*([A-Za-z]+)?$/.test(value); 
}, 'Please enter a full name');

jQuery.validator.addMethod('phoneUK', function(phone_number, element) {
return this.optional(element) || phone_number.length > 9 &&
phone_number.match(/^(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}$/);
}, 'Please specify a valid phone number');

jQuery.validator.addMethod("postcodeUK", function(value, element) {
return this.optional(element) || /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i.test(value);
}, "Please specify a valid Postcode");



// Trim any whitespace on focusout
function injectTrim(handler) {
  return function (element, event) {
    if (element.tagName === "TEXTAREA" || (element.tagName === "INPUT" 
      && element.type !== "password")) {
      element.value = $.trim(element.value);
  }
  return handler.call(this, element, event);
};
}


jQuery(".validate-form").each(function(){

jQuery(this).validate({
  onfocusout: injectTrim(jQuery.validator.defaults.onfocusout),

  rules: {
    fullname: {
      
      required: {
        depends:function(){
          jQuery(this).val(jQuery.trim(jQuery(this).val()));
          console.log(jQuery.trim(jQuery(this).val()));
          return true;
        }
      },
      fullname: true,
      minlength: 9,
      maxlength: 10
    },
  },
  rules: {
    phoneNumber: {
      required: true,
      minlength: 9,
      maxlength: 10
      
    }
  },
  rules: {
    emailAddress: {
      required: {
        depends:function(){
          jQuery(this).val(jQuery.trim(jQuery(this).val()));
          return true;
        }
      },
      email: true

    }
  },

  highlight:function(element, errorClass, validClass) {
    jQuery(element).parent('div.input-element').delay(100).removeClass('success');
    jQuery(element).parents('div.input-element').delay(100).addClass('error').stop();


  },
  unhighlight: function(element, errorClass, validClass) {
    jQuery(element).parent('div.input-element').delay(100).removeClass('error').stop();
    jQuery(element).parent('div.input-element').delay(100).addClass('success').stop();
  }
});
});});