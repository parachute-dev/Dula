define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
], function ($) {
    'use strict';

    $.validator.addMethod(
        'validate-file', function (value, elem) {
			var validator     = this;
			var maxFiles      = parseInt($(elem).data('max-files'));
			var minFiles      = parseInt($(elem).data('min-files'));
			var numberOfFiles = $(elem).parents('.bfb-element').find('.bfb-file-list').children().length;

			if (numberOfFiles < minFiles) {
				validator.validateMessage = $.mage.__('Minimum number of required files: %0').replace('%0', minFiles);
				return false;
			}

			if (numberOfFiles > maxFiles) {
				validator.validateMessage = $.mage.__('Maximum number of required files: %0').replace('%0', maxFiles);
				return false;
			}

        	return true;
        },
        function () {
            return this.validateMessage;
        }
    );
});
