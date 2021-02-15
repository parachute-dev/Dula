define([
    'jquery',
    'underscore',
    'Magento_Ui/js/form/element/wysiwyg',
    'Magento_Ui/js/lib/key-codes'
], function ($, _, Wysiwyg, keyCodes) {
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

    return Wysiwyg.extend({
        defaults: {
            activeTabIndex: 0,
            variables: [
                
            ],
            editorVariables: [
                {
                    label: 'Form',
                    options: [
                        {
                            label: 'ID [form_id]',
                            value: '[form_id]'
                        },
                        {
                            label: 'Name [form_name]',
                            value: '[form_name]'
                        },
                        {
                            label: 'Url [form_url]',
                            value: '[form_url]'
                        },
                        {
                            label: 'Submission Count [submission_count]',
                            value: '[submission_count]'
                        }
                    ]
                },
                {
                    label: 'Submission',
                    options: [
                        {
                            label: 'ID [submission_id]',
                            value: '[submission_id]'
                        },
                        {
                            label: 'Date [submission_date]',
                            value: '[submission_date]'
                        },
                        {
                            label: 'Content [submission_content]',
                            value: '[submission_content]'
                        },
                        {
                            label: 'From Page [submit_from_page]',
                            value: '[submit_from_page]'
                        }
                    ]
                },
                {
                    label: 'Other',
                    options: [
                        {
                            label: 'Visitor IP [visitor_ip]',
                            value: '[visitor_ip]'
                        }
                    ]
                }
            ],
            listVisible: false,
            filterOptions: [],
            filterOptionsFocus: false,
            filterInputValue: '',
            service: {
                template: 'BlueFormBuilder_Core/form/smart-variables'
            },
            cols: 15,
            rows: 2,
            listens: {
                filterInputValue: 'filterOptionsList'
            },
            editing: false,
            showHideEditorBtn: '',
            editorId: ''
        },

        initialize: function () {
            _.bindAll(this,
                'toggleListVisible'
            );

            this._super();

            return this;
        },

        /**
         * Calls 'initObservable' of parent, initializes 'options' and 'initialOptions'
         *     properties, calls 'setOptions' passing options to it
         *
         * @returns {Object} Chainable.
         */
        initObservable: function () {
            this._super();
            this.observe([
                'listVisible',
                'options',
                'filterOptions',
                'filterOptionsFocus',
                'filterInputValue',
                'editing'
            ]);

            return this;
        },

        toggleOptionSelected: function (data) {
            var value = this.value();
            value += data.value;
            this.value(value);
            this.listVisible(false);
            if (this.editorId && (typeof tinyMCE.get(this.editorId) === 'object')) {
                tinyMCE.get(this.editorId).setContent(value);
            }
        },

        /**
         * Toggle list visibility
         *
         * @returns {Object} Chainable
         */
        toggleListVisible: function () {
            if (!this.disabled()) {
                this.listVisible(!this.listVisible());
                this.loadFormFields();
            }
            return this;
        },

        loadFormFields: function () {
            if (this.listVisible()) {
                var elements   = _.clone(this.source.get('magezonBuilder.profile.elements'));
                if (this.editorMode) {
                    var variables = _.clone(this.editorVariables);
                } else {
                    var variables = _.clone(this.variables);
                }
                var formFields = {
                    label: 'Fields'
                };
                var result          = parseOptions(elements, 'elements');
                var fields          = result.cacheOptions.plain;
                var builderElements = this.source.get('magezonBuilder.profile.builderElements');
                var result          = [];
                _.each(fields, function(_element) {
                    var builderElement = _.findWhere(builderElements, {type: _element['type']});
                    if (builderElement && builderElement.control) {
                        var value = '[' + _element.elem_name + ']';
                        result.push({
                            label: _element.label + ' ' + value,
                            value: value
                        });
                    }
                });
                formFields['options'] = result;
                variables.unshift(formFields);
                this.options(variables);
            }
        },

        /**
         * Handler outerClick event. Closed options list
         */
        outerClick: function () {
            if (!this.editing()) {
                this.listVisible() ? this.listVisible(false) : false;
            }
        },

        /**
         * Handler keydown event to filter options input
         *
         * @returns {Boolean} Returned true for emersion events
         */
        filterOptionsKeydown: function (data, event) {
            var key = keyCodes[event.keyCode];

            !this.isTabKey(event) ? event.stopPropagation() : false;

            if (key === 'pageDownKey' || key === 'pageUpKey') {
                event.preventDefault();
                this.filterOptionsFocus(false);
            }

            return true;
        },

        /**
         * Checked key name
         *
         * @returns {Boolean}
         */
        isTabKey: function (event) {
            return keyCodes[event.keyCode] === 'tabKey';
        },

        getFullOptions: function() {
            var options = [];
            _.each(this.options(), function (row) {
                _.each(row.options, function (option) {
                    options.push(option);
                });
            });

            return options;
        },

        /**
         * Filtered options list by value from filter options list
         */
        filterOptionsList: function () {
            var value = this.filterInputValue().trim().toLowerCase(),
                array = [];

            var options = this.getFullOptions();

            if (this.filterInputValue()) {
                array = this._getFilteredArray(options, value);
                if (!value.length) {
                    this.filterOptions(options);
                } else {
                    this.filterOptions(array);
                }
                return false;
            }

            this.filterOptions(options);
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

            for (i; i < list.length; i++) {
                curOption = list[i].label.toLowerCase();

                if (curOption.indexOf(value) > -1) {
                    array.push(list[i]); /*eslint max-depth: [2, 4]*/
                }
            }

            return array;
        },

        activeTab: function(e, e1, selector) {
            var targetSelector = $(selector.currentTarget);
            targetSelector.parent().children().removeClass('active');
            targetSelector.addClass('active');
            var parent = targetSelector.parents('.bfb-smart-variables');
            var $index = targetSelector.data('index');
            parent.find('.bfb-tab-right .bfb-tab-content-item').removeClass('active');
            parent.find('.bfb-tab-right .bfb-tab-content-item[data-index=' + $index + ']').addClass('active');
            this.filterOptionsFocus(false);
        },

        loadFilterOptions: function() {
            this.filterOptionsFocus(true);
            var options = this.getFullOptions();
            if (!this.filterInputValue()) {
                this.filterOptions(options);
            }
        },

        onElementRender: function (e) {
            var self = this;
            setTimeout(function () {
                self.initButton(e);
            }, 500);
        },

        initButton: function (e) {
            var self               = this;
            var html               = '<button type="button" class="scalable bfb-smart-btn"><i aria-hidden="true" class="fas mgz-fa-list-ul bfb-smart-action"></i> <span>Insert Form Variables</span></button>';
            var parent             = $(e).parents('.admin__field-control');
            this.showHideEditorBtn = parent.find('.action-show-hide');
            if (this.showHideEditorBtn.length) {
                this.editorId = this.showHideEditorBtn.attr('id').replace('toggle', '');
                $('#' + this.editorId).addClass('bfb-elem-smart-variables');
                $(html).bind('click', function(e) {
                    event.preventDefault();
                    self.editing(true);
                    parent.find('.bfb-smart-variables .bfb-smart-action').trigger('click');
                    setTimeout(function () {
                        self.editing(false);
                    }, 200);
                }).insertAfter(this.showHideEditorBtn);
            }
        }
    });
});