define([
    'underscore',
    'BlueFormBuilder_Core/js/form/builder-fields'
], function (_, Field) {
    'use strict';

    return Field.extend({
    	defaults: {
    		filterOptions: true
    	},

        setNewOptions: function(options) {
        	if (!this.allFields) {
				var builderElements = this.getBuilderElements();
				var newOptions      = [];
	        	_.each(options, function(option) {
	        		var builderElement = _.findWhere(builderElements, {type: option['type']});
	        		if (builderElement.conditional) {
	        			newOptions.push(option);
	        		}
	        	});
	        	options = newOptions;
	        }
            this.options(options);
            this.cacheOptions.plain = options
        },

        toggleOptionSelected: function (data) {
            if (!this.allFields) {
                var builderElements = this.getBuilderElements();
                var builderElement  = _.findWhere(builderElements, {type: data['type']});
                if (!builderElement.conditional) {
                    return this;
                }
            }
            return this._super(data);
        }
    });
});