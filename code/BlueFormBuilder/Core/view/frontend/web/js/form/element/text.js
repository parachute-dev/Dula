define([
    'ko',
    'jquery',
    'uiComponent',
    'BlueFormBuilder_Core/js/jquery.inputmask.bundle.min'
], function (ko, $, Component) {
    'use strict';

    ko.bindingHandlers.inputmask = {
        init: function (element, valueAccessor, allBindingsAccessor) {
            var mask = valueAccessor();
            var observable = mask.value;
            if (ko.isObservable(observable)) {
                $(element).on('focusout change', function () {
                    if ($(element).inputmask('isComplete')) {
                        observable($(element).val());
                    } else {
                        observable(null);
                    }
                });
            }
            $(element).inputmask(mask);
        },
        update: function (element, valueAccessor, allBindings, viewModel, bindingContext) {
            var mask = valueAccessor();
            var observable = mask.value;
            if (ko.isObservable(observable)) {
                var valuetoWrite = observable();
                $(element).val(valuetoWrite);
            }
        }

    };
 
    return Component.extend({
        defaults: {
            listVisible: false,
            options: [],
            autoOptions: [],
            showMsg: true
        },

        initialize: function () {

            this._super();

            this.observe('value limit showMsg options autoOptions listVisible');

            this.limit(this.max);

            this.showMsg(true);

            this.on('value', this.onUpdate.bind(this));

            this.processValue(this.value(), true);

            var options = JSON.parse(this.options());
            this.options(options);
        },

        processValue: function(value, initialize) {

            if (value.indexOf('[') !== -1) {
                value = '';
            }

            this.onUpdate(value, initialize);
            return this.value();
        },

        onUpdate: function(val, initialize) {

            if (!val || val == ' ') {
                val = '';
            }

            if (this.max) {
                var limit;

                if (!val) {
                    limit = this.max;
                } else {
                    if (this.limitType == 'characters') {
                        limit = this.max - this.getTotalChars(val);
                    } else {
                        limit = this.max - this.getTotalWords(val);    
                    }
                }
                
                if (limit <= 0) {
                    if (this.limitType == 'characters') {
                        val = val.substr(0, this.max);
                    } else {
                        val = val.split(" ").splice(0, this.max).join(" ");
                    }
                    this.limit(0);
                } else {
                    this.limit(limit);
                }
            } else {
                this.limit(this.getTotalChars(val));
            }

            if (this.options().length && val) {
                var result = this._getFilteredArray(this.options(), val.trim().toLowerCase());

                if (!initialize) {
                    if (result.length) {
                        this.listVisible(true);
                    } else {
                        this.listVisible(false);
                    }
                }

                this.autoOptions([]);
                this.autoOptions(result);
            } else {
                this.listVisible(false);
            }
            if (val==' ') {
                val = null;
            }

            this.value(val);

            $(this.element).trigger('bfb:change');
        },

        getTotalWords: function(val) {
            var regex = /\s+/gi;
            return val.trim().replace(regex, ' ').split(' ').length;
        },

        getTotalChars: function(val) {
            return val.length;
        },

        /**
         * Filtered options list by value from filter options list
         *
         * @param {Array} list - option list
         * @param {String} value
         *
         * @returns {Array} filters result
         */
        _getFilteredArray: function (list, value) {
            var i = 0,
                array = [],
                curOption;

            _.each(list, function (opt) {
                var _opt = opt.toLowerCase();
                if (_opt.indexOf(value) > -1) {
                    array.push(opt);
                }
            });

            return array;
        },

        toggleOptionSelected: function (data) {
            this.value(data);
            this.onUpdate(data);
            this.listVisible(false);
        },

        /**
         * Handler outerClick event. Closed options list
         */
        outerClick: function () {
            this.listVisible() ? this.listVisible(false) : false;
        },

        onClick: function() {
            this.onUpdate(this.value());
        }
    });
});