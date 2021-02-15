define([
    'ko',
    'underscore',
    'jquery',
    'Magento_Ui/js/form/element/ui-select'
], function (ko, _, $, Select) {
    'use strict';

    /**
     * Processing options list
     *
     * @param {Array} array - Property array
     * @param {String} separator - Level separator
     * @param {Array} created - list to add new options
     *
     * @return {Array} Plain options list
     */
    function flattenCollection(array, separator, created) {
        var i = 0,
            length,
            childCollection;

        array = _.compact(array);
        length = array.length;
        created = created || [];

        for (i; i < length; i++) {
            created.push(array[i]);

            if (array[i].hasOwnProperty(separator)) {
                childCollection = array[i][separator];
                delete array[i][separator];
                flattenCollection.call(this, childCollection, separator, created);
            }
        }

        return created;
    }

    /**
     * Set levels to options list
     *
     * @param {Array} array - Property array
     * @param {String} separator - Level separator
     * @param {Number} level - Starting level
     * @param {String} path - path to root
     *
     * @returns {Array} Array with levels
     */
    function setProperty(array, separator, level, path) {
        var i = 0,
            length,
            nextLevel,
            nextPath;

        array = _.compact(array);
        length = array.length;
        level = level || 0;
        path = path || '';

        for (i; i < length; i++) {
            if (array[i]) {
                _.extend(array[i], {
                    level: level,
                    path: path
                });
            }

            if (array[i].hasOwnProperty(separator)) {
                nextLevel = level + 1;
                nextPath = path ? path + '.' + array[i].label : array[i].label;
                setProperty.call(this, array[i][separator], separator, nextLevel, nextPath);
            }
        }

        return array;
    }

    /**
     * Preprocessing options list
     *
     * @param {Array} nodes - Options list
     *
     * @return {Object} Object with property - options(options list)
     *      and cache options with plain and tree list
     */
    function parseOptions(nodes) {
        var caption,
            value,
            cacheNodes,
            copyNodes;

        nodes = setProperty(nodes, 'optgroup');
        copyNodes = JSON.parse(JSON.stringify(nodes));
        cacheNodes = flattenCollection(copyNodes, 'optgroup');

        nodes = _.map(nodes, function (node) {
            value = node.value;

            if (value == null || value === '') {
                if (_.isUndefined(caption)) {
                    caption = node.label;
                }
            } else {
                return node;
            }
        });

        return {
            options: _.compact(nodes),
            cacheOptions: {
                plain: _.compact(cacheNodes),
                tree: _.compact(nodes)
            }
        };
    }

    return Select.extend({
        defaults: {
            multipleLevel: false,
            allFields: false,
            links: {
                elements: '${ $.provider }:magezonBuilder.profile.elements'
            },
            listens: {
                elements: 'updateOptions'
            }
        },

        getBuilderElements: function() {
            return this.source.get('magezonBuilder.profile.builderElements');
        },

        getBuilderFields: function() {
            var elements = _.clone(this.source.get('magezonBuilder.profile.elements'));
            var result   = parseOptions(elements, 'elements');
            return result;
        },

        /**
         * Toggle list visibility
         *
         * @returns {Object} Chainable
         */
        toggleListVisible: function () {
            var self = this;
            this.options([]);
            var fields = this.getBuilderFields();
            if (this.multipleLevel) {
                this.setNewOptions(fields.options);
            } else {
                var builderElements = this.getBuilderElements();
                var result          = [];
                _.each(fields.cacheOptions.plain, function(_element) {
                    var builderElement = _.findWhere(builderElements, {type: _element['type']});
                    if (builderElement.control || self.allFields) {
                        result.push(_element);
                    }
                });
                this.setNewOptions(result);
            }
            this.cacheOptions.plain = fields.cacheOptions.plain;
            this.listVisible(!this.listVisible());
            return this;
        },

        updateOptions: function() {
            var self            = this;
            var selected        = this.value();
            var options         = [];
            var values          = [];
            var fields          = this.getBuilderFields();
            var builderElements = this.getBuilderElements();
            _.each(fields.cacheOptions.plain, function(_element) {
                var builderElement = _.findWhere(builderElements, {type: _element['type']});
                if (builderElement.control || self.allFields) {
                    values.push(_element['id']);
                    if (_.isArray(selected) ? _.contains(selected, _element['id']) : (selected === _element['id'])) {
                        options.push(_element);
                    }
                }
            });
            if (this.multiple) {
                var value = [];
                for (var i = 0; i < selected.length; i++) {
                    if ($.inArray(selected[i], values) === -1) {
                        this.value.remove(selected[i]);
                    }
                }
            } else {
                if (selected.length && $.inArray(selected, values) === -1) {
                    this.value(null);
                }
            }
            var selected = this.value();
            this.cacheOptions.plain = fields.cacheOptions.plain;
            this.setNewOptions(options);
            this.value(selected);
            this.setCaption();
        },

        setNewOptions: function(options) {
            this.options(options);
        }
    });
});