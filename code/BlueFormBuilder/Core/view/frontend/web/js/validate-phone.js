define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
], function ($) {
    'use strict';

    $.validator.addMethod(
        'validate-phone', function (value, element) {
            if (value.trim() &&window.intlTelInputGlobals) {
                var iti = window.intlTelInputGlobals.instances[element.getAttribute('data-intl-tel-input-id')];
                return iti.isValidNumber();
            }
            return true;
        }, $.mage.__('Please enter a valid phone number.'));
});