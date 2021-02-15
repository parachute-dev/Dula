define([
    'jquery',
    'Magento_Ui/js/lib/validation/rules'
], function ($, Rules) {
    'use strict';

    for (var i in Rules) {
    	if (!$.validator.methods[i]) {
        	$.validator.addMethod(i, Rules[i]['handler'], Rules[i]['message']);
    	}
    }
});
