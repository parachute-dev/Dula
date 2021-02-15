define([
    'jquery',
    'uiComponent'
], function ($, Component) {
    'use strict';
 
    return Component.extend({
        defaults: {
            qty: ''
        },

        initialize: function () {
            this._super();
            this.observe('qty');
            this.qty(this.default);
            $('#' + this.index).val(this.default);
            $('#' + this.index).trigger('change');
            $('#' + this.index).trigger('bfb:change');
            this.on('qty', this.onUpdate.bind(this));
        },

        onUpdate: function(val, initialize) {
            $('#' + this.index).trigger('change');
            $('#' + this.index).trigger('bfb:change');
        },
 
        decreaseQty: function() {
            var max  = parseFloat(this.max);
            var min  = parseFloat(this.min);
            var step = parseFloat(this.step);
            var qty  = parseFloat(this.qty());
            if (this.min === null) {
                var newQty = qty - parseFloat(step);
                this.qty(newQty);
            } else {
                if ((max && max > min) || !max) {
                    var newQty = qty - parseFloat(step);
                    if (newQty < min) {
                        newQty = min;
                    }
                    this.qty(newQty);
                } else {
                    this.qty(min);
                }
            }
            $('#' + this.index).trigger('change');
            $('#' + this.index).trigger('bfb:change');
        },
 
        increaseQty: function() {
            var max  = parseFloat(this.max);
            var min  = parseFloat(this.min);
            var step = parseFloat(this.step);
            var qty  = parseFloat(this.qty());
            if (this.max === null) {
                var newQty = qty + step;
                this.qty(newQty);
            } else {
                if ((max && max > min) || !max) {
                    var newQty = qty + step;
                    if (newQty > max) {
                        newQty = max;
                    }
                    this.qty(newQty);
                } else {
                    this.qty(min);
                }
            }
            $('#' + this.index).trigger('change');
            $('#' + this.index).trigger('bfb:change');
        }
    });
});