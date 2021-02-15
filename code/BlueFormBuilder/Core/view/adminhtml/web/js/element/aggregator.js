define([
    'jquery',
    'Magento_Ui/js/form/element/ui-select',
    'uiRegistry'
], function ($, Select, registry) {
    'use strict';

    return Select.extend({

        defaults: {
            value: '',
            clearing: false,
            parentContainer: '',
            parentSelections: '',
            changer: ''
        },

        /**
         * @inheritdoc
         */
        initObservable: function () {
            this._super().observe('value');
            if (this.value() === '' && this.default) {
                this.value(this.default);
            }
            this.updateValue();

            return this;
        },

        updateValue: function () {
            var records = registry.get(this.retrieveParentName(this.parentSelections)),
                index = this.index,
                uid = this.uid;

            var self = this;

            var value = '';
            records.elems.each(function (record) {
                record.elems.filter(function (comp) {
                    return comp.index === index && comp.uid !== uid;
                }).each(function (comp) {
                    value = comp.value();
                });
            });
            if (value) {
                this.value(value);
            }
        },

        /**
         * @inheritdoc
         */
        onUpdate: function () {
            this._super();
            this.clearValues();
        },

        /**
         * Clears values in components like this.
         */
        clearValues: function () {
            var records = registry.get(this.retrieveParentName(this.parentSelections)),
                index = this.index,
                uid = this.uid;

            var self = this;

            records.elems.each(function (record) {
                record.elems.filter(function (comp) {
                    return comp.index === index && comp.uid !== uid;
                }).each(function (comp) {
                    comp.value(self.value());
                });
            });
        },

        /**
         * Retrieve name for the most global parent with provided index.
         *
         * @param {String} parent - parent name.
         * @returns {String}
         */
        retrieveParentName: function (parent) {
            return this.name.replace(new RegExp('^(.+?\\.)?' + parent + '\\..+'), '$1' + parent);
        }
    });
});