define([
    'jquery',
    'Magento_Ui/js/dynamic-rows/dynamic-rows-grid',
    'underscore',
    'mageUtils'
], function ($, DynamicRows, _, utils) {
    'use strict';

    return DynamicRows.extend({
        defaults: {
            update: true,
            dndConfig: {
            	enabled: false
            },
            pageSize: 99999,
            mappingSettings: {
                enabled: false,
                distinct: false
            },
            map: {
                'option_id': 'option_id'
            },
            identificationProperty: 'option_id',
            identificationDRProperty: 'option_id',
            modules: {
                enableComponent: '${ $.parentName }.affect_conditional'
            }
        },

        initialize: function () {
            this._super();

            _.bindAll(this,
                'updateTrigger',
                'processingDeleteRecord'
            );

            return this;
        },

        updateTrigger: function (val) {
            this.trigger('update', val);
            if (this.enableComponent()) {
                this.enableComponent().value(1);
            }
        },

        /**
         * Calls 'initObservable' of parent
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            this._super()
                .observe([
                    'showSpinner'
                ]);

            return this;
        },

        /**
         * Processing pages before deleteRecord
         *
         * @param {Number|String} index - element index
         * @param {Number|String} recordId
         */
        processingDeleteRecord: function (index, recordId) {
            this.deleteRecord(index, recordId);
        },

        /** @inheritdoc */
        processingInsertData: function (data) {
            var options = [],
                currentOption,
                generalContext = this;

            if (!data) {
                return;
            }
            _.each(data, function (item) {
                if (!item.options) {
                    return;
                }
                _.each(item.options, function (option) {
                    currentOption = utils.copy(option);

                    if (currentOption.hasOwnProperty('sort_order')) {
                        delete currentOption['sort_order'];
                    }

                    if (currentOption.hasOwnProperty('option_id')) {
                        delete currentOption['option_id'];
                    }

                    if (currentOption.values.length > 0) {
                        generalContext.removeOptionsIds(currentOption.values);
                    }
                    options.push(currentOption);
                });
            });

            if (!options.length) {
                return;
            }
            this.cacheGridData = options;
            _.each(options, function (opt) {
                this.mappingValue(opt);
            }, this);

            this.insertData([]);
        },

        /**
         * Removes option_id and option_type_id from every option
         *
         * @param {Array} options
         */
        removeOptionsIds: function (options) {
            _.each(options, function (optionValue) {
                delete optionValue['option_id'];
                delete optionValue['option_type_id'];
            });
        },

        /** @inheritdoc */
        processingAddChild: function (ctx, index, prop) {
            if (!ctx) {
                this.showSpinner(true);
                this.addChild(ctx, index, prop);

                return;
            }

            this._super(ctx, index, prop);
        },

        /**
         * Set empty array to dataProvider
         */
        clearDataProvider: function () {
            this.source.set(this.dataProvider, []);
        },

        /**
         * Mutes parent method
         */
        updateInsertData: function () {
            return false;
        },

        getNewData: function(data) {
            var changes = [],
                tmpObj = {};

            if (data && data.length !== this.relatedData.length) {
                _.each(data, function (obj) {
                    if (obj && obj[this.identificationDRProperty]) {
                        tmpObj[this.identificationDRProperty] = obj[this.identificationDRProperty];

                        if (!_.findWhere(this.relatedData, tmpObj)) {
                            changes.push(obj);
                        }
                    }
                }, this);
            }

            return changes;
        },

        /**
         * Filtering data and calculates the quantity of pages
         *
         * @param {Array} data
         */
        parsePagesData: function (data) {
            var pages = [];

            this.relatedData = data;

            if (this.relatedData) {
                pages = Math.ceil(this.relatedData.length / this.pageSize) || 1;
            }
            this.pages(pages);
        },

        /**
         * Set data from recordData to insertData
         */
        setToInsertData: function () {
            if (this.recordData()) {
                this._super();
            }
        }
    });
});
