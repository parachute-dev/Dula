define([
    'jquery',
    'underscore',
    'Magezon_Builder/js/ui/form/element/builder'
], function ($, _, BUILDER) {
    'use strict';

    return BUILDER.extend({

    	initialize: function () {
            window['bfb'] = {};
            this._super();
            this.source.set('magezonBuilder.' + this.index + '.builderElements', this.elements);
            window['bfb']['builderElements'] = this.elements;
            var elements = [];
            try {
                elements = this.getElements();
            } catch(err) {

            }
            if (this.source) {
                this.source.set('magezonBuilder.' + this.index + '.elements', elements);
            }
            return this;
        },

        setDifferedFromDefault: function () {
        	this._super();
            var elements = [];
        	try {
        		elements = this.getElements();
        	} catch(err) {}
            if (this.source) {
                this.source.set('magezonBuilder.' + this.index + '.elements', elements);
                window['bfb']['elements'] = elements;
                $(document).trigger('loadBuilderElements');
            }
        },

		getElements: function() {
            var builderElements = this.elements;
            var profile         = JSON.parse(this.value());
            var processElements = function(elements) {
                var children = [];
                _.each(elements, function(_element, index) {
                    var builderElement = _.findWhere(builderElements, {type: _element['type']});
                    if (builderElement) {
                        var newElement      = _element;
                        newElement['value'] = _element.id;
                        newElement['label'] = _element.builderName ? _element.builderName : (_element.label ? _element.label : builderElement.name);
                        if (_element.elements && _element.elements.length) newElement['optgroup'] = processElements(_element.elements);
                        children.push(newElement);
                    }
                });
                return children;
            }
            return processElements(profile.elements);
		}
    })
});