/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
/*jshint jquery:true browser:true*/
/*global BASE_URL:true*/
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        define([
            "jquery",
            "underscore",
            'Magento_Ui/js/modal/alert',
            "jquery/ui",
            "jquery/validate",
            "mage/translate",
            "mage/validation"
        ], factory);
    } else {
        factory(jQuery);
    }
}(function ($, _, alert) {
    "use strict";

    $.extend(true, $.validator.prototype, {
        /**
         * Focus invalid fields
         */
        focusInvalid: function() {
            if (this.settings.focusInvalid) {
                try {
                    $(this.errorList.length && this.errorList[0].element || [])
                        .focus()
                        .trigger("focusin");
                } catch (e) {
                    // ignore IE throwing errors when focusing hidden elements
                }
            }
        },
        elements: function () {
            var validator = this,
                rulesCache = {};

            var elements = $(this.currentForm)
                .find("input, select, textarea")
                .not(this.settings.forceIgnore)
                .not(':submit, :reset, :image, [disabled]')
                .not(this.settings.ignore)
                .filter(function () {
                    if (!this.name && validator.settings.debug && window.console) {
                        console.error('%o has no name assigned', this);
                    }

                    // select only the first element for each name, and only those with rules specified
                    if (this.name in rulesCache || !validator.objectLength($(this).rules())) {
                        return false;
                    }

                    rulesCache[this.name] = true;

                    return true;
                });

            // select all valid inputs inside the form (no submit or reset buttons)
            return elements;
        }
    });

    $.extend($.fn, {
        /**
         * ValidationDelegate overridden for those cases where the form is located in another form,
         *     to avoid not correct working of validate plug-in
         * @override
         * @param {string} delegate - selector, if event target matched against this selector,
         *     then event will be delegated
         * @param {string} type - event type
         * @param {function} handler - event handler
         * @return {Element}
         */
        validateDelegate: function (delegate, type, handler) {
            return this.on(type, $.proxy(function (event) {
                var target = $(event.target);
                var form = target[0].form;
                if(form && $(form).is(this) && $.data(form, "validator") && target.is(delegate)) {
                    return handler.apply(target, arguments);
                }
            }, this));
        }
    });

    $.widget("validation", $.mage.validation, {
        options: {
            ignore: ':disabled, .ignore-validate, .no-display.template, ' +
                ':disabled input, .ignore-validate input, .no-display.template input, ' +
                ':disabled select, .ignore-validate select, .no-display.template select, ' +
                ':disabled textarea, .ignore-validate textarea, .no-display.template textarea, ' +
                '.bfb-state-hidden input, .bfb-state-hidden select, .bfb-state-hidden textarea' +
                '.mgz-hidden input, .mgz-hidden select, .mgz-hidden textarea'
        },

        /**
         * Validation creation
         * @protected
         */
        _create: function () {
            this.validate = this.element.validate(this.options);

            // ARIA (adding aria-required attribute)
            this.element
                .find('.field.required')
                .find('.control')
                .find('input, select, textarea')
                .attr('aria-required', 'true');
        }
    });

    return $.validation;
}));