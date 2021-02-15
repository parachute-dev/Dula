define([
    'jquery',
    'underscore',
    'mage/template',
    'mage/mage',
    'BlueFormBuilder_Core/js/validation',
    'BlueFormBuilder_Core/js/form/element/text',
    'BlueFormBuilder_Core/js/form/element/number'
], function ($, _, mageTemplate) {
    'use strict';

    var uniqueid = function (size) {
        var code = Math.random() * 25 + 65 | 0,
            idstr = String.fromCharCode(code);

        size = size || 12;

        while (idstr.length < size) {
            code = Math.floor(Math.random() * 42 + 48);

            if (code < 58 || code > 64) {
                idstr += String.fromCharCode(code);
            }
        }

        return idstr.toLowerCase();
    };

    $.widget('bfb.form', {

        options: {
            validCurrentPage: true
        },

        elems: [],

        /**
         *
         * @private
         */
        _create: function () {

            this.elems = this.element.find('input, select, textarea');

            this.initValidation();

            this.initListeners();

            this.initConditional();

            this.loadData();

            $('.bfb-element-page.active').removeClass('bfb-animated');

            this.updatePagesIndicator();
        },

        initValidation: function () {
            var self = this;

            this.element.mage('validation', {

                /** @inheritdoc */
                errorPlacement: function (error, element) {

                    var parent = element.parents('.bfb-element-starratings');
                    if (parent.length) {
                        parent.find('input').eq(0).trigger('click');
                        parent.find('.bfb-element-stars').after(error);
                    } else {
                        var $parent = element.parents('.bfb-element-control');
                        if ($parent.find('.bfb-error').eq(0).length) {
                            $parent.siblings(this.errorElement + '.' + this.errorClass).remove();
                            var $errorSelector = $parent.find('.bfb-error').eq(0);
                            if ($errorSelector.data('single-error')) {
                                $errorSelector.html(null);
                            }
                            $errorSelector.append(error);
                            
                        } else {
                            var errorPlacement = element,
                                fieldWrapper;

                            // logic for date-picker error placement
                            if (element.hasClass('_has-datepicker')) {
                                errorPlacement = element.siblings('button');
                            }
                            // logic for field wrapper
                            fieldWrapper = element.closest('.addon');

                            if (fieldWrapper.length) {
                                errorPlacement = fieldWrapper.after(error);
                            }
                            //logic for checkboxes/radio
                            if (element.is(':checkbox') || element.is(':radio')) {
                                errorPlacement = element.parents('.control').children().last();

                                //fallback if group does not have .control parent
                                if (!errorPlacement.length) {
                                    errorPlacement = element.siblings('label').last();
                                }
                            }
                            //logic for control with tooltip
                            if (element.siblings('.tooltip').length) {
                                errorPlacement = element.siblings('.tooltip');
                            }
                            errorPlacement.after(error);
                        }
                    }
                },

                submitHandler: function () {
                    if ($.isEmptyObject(self.element.validate().invalid)) {
                        self.ajaxSubmit($(self.element));
                    }
                }
            });

            this.element.on('invalid-form.validate', function (event, validation) {
                var firstActive = $(validation.errorList[0].element || []);
                var validator   = self.element.validate();
                if (firstActive.length && firstActive.is(':hidden')) {
                    var parents = firstActive.parents('.mgz-tabs-tab-content');
                    var status;
                    parents.each(function(index, el) {
                        if (!$(this).hasClass('mgz-active') && !status && !$(this).hasClass('.bfb-state-hidden')) {
                            status = true;
                            var id = $(this).attr('id');
                            if (id) $('a[href="#' + id + '"]').trigger('click');
                            validator.element(firstActive)
                        }
                    });
                }
            });
        },

        initListeners: function () {
            var self = this;
            this.element.find('.bfb-pages .mgz-tabs-tab-title a').each(function(index, el) {
                $(this).on('click', function (e) {
                    var _id = $(this).attr('href');
                    $(_id).removeClass('bfb-animated');
                })
            });

            this.element.find('.action-next').each(function(index, el) {
                $(this).on('click', function (e) {
                    var currentPage = $(this).closest('.bfb-pages');
                    if ((self.options.validCurrentPage && self.validPage(currentPage.find('.mgz-tabs-tab-content')))
                        || !self.options.validCurrentPage
                    ) {
                        var anchors = currentPage.find('.mgz-tabs-nav').children();
                        var status  = false;
                        var nextAnchor;
                        anchors.each(function(index, el) {
                            if (status  && !$(this).hasClass('.bfb-state-hidden') && !nextAnchor) {
                                nextAnchor = $(this);
                                return true;
                            }
                            if ($(this).hasClass('mgz-active')) {
                                status = true;
                            }
                        });
                        if (nextAnchor) nextAnchor.trigger('click');
                        self.updatePagesIndicator();
                    }
                });
            });

            this.element.find('.action-prev').each(function(index, el) {
                $(this).on('click', function (e) {
                    var currentPage = $(this).closest('.bfb-pages');
                    var anchors     = currentPage.find('.mgz-tabs-nav').children();
                    var status      = true;
                    var prevAnchor;
                    anchors.each(function(index, el) {
                        if ($(this).hasClass('mgz-active')) {
                            status = false;
                        }
                        if (status) prevAnchor = $(this);
                    });
                    if (prevAnchor) prevAnchor.trigger('click');
                    self.updatePagesIndicator();
                });
            });
        },

        initSaveProgressListeners: function () {
            var self = this;
            var cacheEvents = [];
            self.elems.each(function(index, el) {
                $(this).change(function(y) {
                    if ($.inArray(y.timeStamp, cacheEvents)===-1) {
                        cacheEvents.push(y.timeStamp);
                        self.saveProgress();
                    }
                });
            });
        },

        updatePagesIndicator: function() {
            var self = this;
            this.element.find('.bfb-pages').each(function(index, el) {
                self.updatePageIndicator($(this));
                $(this).children('.bfb-page-indicator').show();
            });
        },

        htmlDecode: function(input){
            var e = document.createElement('div');
            e.innerHTML = input;
            return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
        },

        updatePageIndicator: function (pages) {
            var html = '', data = {}, fileTmpl;
            var indicator = $(pages).data('indicator');
            if (indicator=='circles' || indicator=='progress' || indicator=='connector') {
                var fileTmpl  = mageTemplate($('#' + pages.data('id') + '-indicator-' + indicator).html());

                if (indicator=='circles') {
                    $(pages).children('.mgz-tabs-nav').children().not('.mgz-hidden').each(function(index, el) {
                        data = {};
                        if ($(this).hasClass('mgz-active')) data['classes'] = 'mgz-active';
                        data['index'] =  index + 1;
                        data['title'] =  $(this).children('a').html();
                        html += fileTmpl({
                            data: data
                        });
                    });
                    html = html.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&');
                    $(pages).children('.bfb-page-indicator').html(html).text();
                }

                if (indicator=='progress') {
                    var pageTitle = '';
                    var skip = false;
                    var currentIndex = 1;
                    $(pages).children('.mgz-tabs-nav').children().not('.mgz-hidden').each(function(index, el) {
                        if ($(this).hasClass('mgz-active')) {
                            pageTitle = $(this).find('a').html();
                        }
                        if ($(this).hasClass('mgz-active')) {
                            skip = true;
                        }
                        if (!skip) {
                            currentIndex++;
                        }
                    });
                    var total = $(pages).children('.mgz-tabs-nav').children().not('.mgz-hidden').length;
                    data = {};
                    data['title']   = pageTitle;
                    data['total']   = total;
                    data['current'] = currentIndex;
                    data['width']   = (currentIndex / total * 100) + '%';
                    html = fileTmpl({
                        data: data
                    });
                    html = html.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&');
                    $(pages).children('.bfb-page-indicator').html(html).text();
                }

                if (indicator=='connector') {
                    var total = $(pages).children('.mgz-tabs-nav').children().not('.mgz-hidden').length;
                    $(pages).children('.mgz-tabs-nav').children().not('.mgz-hidden').each(function(index, el) {
                        data = {};
                        if ($(this).hasClass('mgz-active')) data['classes'] = 'mgz-active';
                        data['index'] =  index + 1;
                        data['title'] =  $(this).children('a').html();
                        data['width'] = (100 / total) + '%';
                        html += fileTmpl({
                            data: data
                        });
                    });
                    html = html.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&');
                    $(pages).children('.bfb-page-indicator').html(html).text();
                }
            }
        },

        initConditional: function () {
            if (!this.options.conditional) return;
            var self = this;
            var cacheEvents = [];

            var conditional = this.options.conditional;
            var logic       = {};
            var elements    = {};
            for (var i = 0; i < conditional.length; i++) {
                for (var x = 0; x < conditional[i]['conditions'].length; x++) {
                    var row = conditional[i]['conditions'][x];
                    var fieldSelector = $('.' + row.field);
                    if (fieldSelector.length) {
                        fieldSelector.find('input, select, textarea').each(function(index, el) {
                            var uniq = $(this).attr('bfb-uniq');
                            if (!uniq) {
                                uniq = uniqueid();
                                $(this).attr('bfb-uniq', uniq);
                            }
                            if (!logic.hasOwnProperty(uniq)) {
                                logic[uniq] = {};
                            }
                            if (!logic[uniq].hasOwnProperty(i)) {
                                logic[uniq][i] = conditional[i];
                                if (!elements.hasOwnProperty(uniq)) {
                                    elements[uniq] = $(this);
                                }
                            }
                        });
                    }
                }
            }
            this.logic = logic;

            _.each(elements, function(elem) {
                var jEvent = 'change';

                if (elem.is(':checkbox') || elem.is(':radio')) {
                    jEvent = 'click';
                }

                self.loadElementValidation(elem, true);

                elem.on('bfb:change', function() {
                    self.loadElementValidation(elem, true);
                });

                elem.on('change', function() {
                    self.loadElementValidation(elem, true);
                });

                if ((elem.is(':checkbox') || elem.is(':radio'))) {
                    self.loadElementValidation(elem, true);
                }
            });
        },

        validPage: function (page) {
            var valid     = true;
            var validator = this.element.validate();
            var elements  = $(page).find('input, select, textarea');

            $(page).addClass('has-valid');

            var firstElement;

            elements.each(function () {
                if ($(this).is(':visible') && ($(this).data('validate') || $(this).hasClass('bfb-validate'))) {
                    if (valid) {
                        valid = validator.element(this);
                    } else {
                        validator.element(this);
                    }

                    if (!valid && !firstElement) {
                        firstElement = $(this);
                    }
                }
            });

            if (firstElement) {
                if (firstElement.hasClass('validate-date')) {
                    firstElement.trigger('click');
                } else {
                    firstElement.focus();    
                }
            }

            return valid;
        },

        getFormValues: function() {
            var self   = this;
            var values = {};
            var array  = this.element.serializeArray();
            $.each(array, function () {
                var name = this.name.replace('[]', '');
                var element = self.element.find('[name="' + this.name + '"]');
                if (!element.parents('.bfb-state-hidden').length) {
                    if (values[name] !== undefined) {
                        if (!values[name].push) {
                            values[name] = [values[name]];
                        }
                        if (this.name.indexOf('[]') === -1) {
                            values[name] = this.value || '';
                        } else {
                            values[name].push(this.value || '');
                        }
                    } else {
                        values[name] = this.value || '';
                    }
                }
            });
            return values;
        },

        isValidDate: function(s) {
            var bits = s.split('/');
            var d = new Date(bits[2] + '/' + bits[1] + '/' + bits[0]);
            return !!(d && (d.getMonth() + 1) == bits[1] && d.getDate() == Number(bits[0]));
        },

        validateConditions: function (conditions, aggregator) {
            var self         = this;
            var valid        = false;
            var $post        = this.getFormValues();
            var formElements = self.options.formElements;

            for (var x = 0; x < conditions.length; x++) {
                var row        = conditions[x];
                if (!aggregator) {
                    aggregator = row['aggregator'];
                }

                var $element = _.findWhere(formElements, {id: row['field']});

                if (!$element || !row.field) continue;

                var $elemName = $element.name;

                if (!$post.hasOwnProperty($elemName)) $post[$elemName] = '';

                var $values = [];
                if (_.isString($post[$elemName])) {
                    $values.push($post[$elemName]);
                } else {
                    $values = $post[$elemName];
                }

                var $postValues = [];
                _.each($values, function($_value, index) {
                    $postValues.push($_value.trim().toLowerCase());
                });

                var rowValue;
                if (row['value']) {
                    rowValue = row['value'].trim().toLowerCase();
                }

                switch(row['operator']) {
                    case 'eq':
                            valid = ($.inArray(rowValue, $postValues)!==-1) ? true : false;
                        break;

                    case 'neq':
                            valid = ($.inArray(rowValue, $postValues)===-1) ? true : false;
                        break;

                    case 'gt':
                            var total = 0;
                            for (var i = 0; i < $postValues.length; i++) {
                                if (this.isValidDate($postValues[i])) {
                                    total = $postValues[i];
                                } else {
                                    total += Number($postValues[i]);   
                                }
                            }
                            if (!isNaN(total) && !isNaN(rowValue)) {
                                valid = (Number(total) > Number(rowValue));
                            } else {
                                valid = (total > rowValue);
                            }
                        break;

                    case 'lt':
                            var total = 0;
                            for (var i = 0; i < $postValues.length; i++) {
                                if (this.isValidDate($postValues[i])) {
                                    total = $postValues[i];
                                } else {
                                    total += Number($postValues[i]);
                                }
                            }
                            if (!isNaN(total) && !isNaN(rowValue)) {
                                valid = (Number(total) < Number(rowValue));
                            } else {
                                valid = (total < rowValue);
                            }
                        break;

                    case 'ct':
                            var str = '';
                            for (var i = 0; i < $postValues.length; i++) {
                                str += $postValues[i];
                            }
                            valid = (str && str.indexOf(rowValue) !== -1);
                        break;

                    case 'dct':
                            var str = '';
                            for (var i = 0; i < $postValues.length; i++) {
                                str += $postValues[i];
                            }
                            valid = (str && str.indexOf(rowValue) === -1);
                        break;

                    case 'sw':
                            valid = false;
                            for (var i = 0; i < $postValues.length; i++) {
                                if ($postValues[i].startsWith(rowValue)) {
                                    valid = true;
                                    break;
                                }
                            }
                        break;

                    case 'ew':
                            valid = false;
                            for (var i = 0; i < $postValues.length; i++) {
                                if ($postValues[i].endsWith(rowValue)) {
                                    valid = true;
                                    break;
                                }
                            }
                        break;

                    case 'et':
                            valid = ($.inArray('', $postValues)!==-1 || $.inArray('0', $postValues)!==-1) ? true : false;
                        break;

                    case 'net':
                            valid = ($.inArray('', $postValues)===-1 && $.inArray('0', $postValues)===-1) ? true : false;

                }

                if (aggregator === 'or' && valid) {
                    break;
                }

                if (aggregator === 'and' && !valid) {
                    break;
                }
            }

            return valid;
        },

        applyActions: function (actions) {

            var self = this;

            for (var x = 0; x < actions.length; x++) {
                var row = actions[x];

                switch(row['action']) {
                    case 'sf':
                            var applyElements = row['apply_field'];
                            for (var i = 0; i < applyElements.length; i++) {
                                var item = $('.' + applyElements[i]);
                                item.removeClass('bfb-state-hidden');
                                item.addClass('bfb-state-shown');
                            }
                        break;

                    case 'hf':
                            var applyElements = row['apply_field'];
                            for (var i = 0; i < applyElements.length; i++) {
                                var item = $('.' + applyElements[i]);
                                item.removeClass('bfb-state-shown');
                                item.addClass('bfb-state-hidden');
                            }
                        break;

                    case 'svo':
                            var applyElement = $('.' + row['action_field'] + ' .bfb-control');
                            var val = row['value'];
                            if (applyElement.is('select') && applyElement.attr('multiple')) {
                                val = val.split(",");
                            }
                            if (applyElement.is(':checkbox')) {
                                val = val.split(",");
                                applyElement.each(function(index, el) {
                                    if ($.inArray($(el).val(), val) !== -1) {
                                        $(el).prop('checked', true);
                                    } else {
                                        $(el).prop('checked', false);
                                    }
                                });
                            } else if (applyElement.is(':radio')) {
                                applyElement.each(function(index, el) {
                                    if ($(el).val() == val) {
                                        $(el).prop('checked', true);
                                    } else {
                                        $(el).prop('checked', false);
                                    }
                                });
                            } else {
                                applyElement.val(val);
                            }
                            applyElement.trigger('change');
                        break;
                }

            }
        },

        revertState: function (actions) {
            var self = this;

            for (var x = 0; x < actions.length; x++) {
                var row = actions[x];

                switch(row['action']) {
                    case 'sf':
                            var applyElements = row['apply_field'];
                            for (var i = 0; i < applyElements.length; i++) {
                                var item = $('.' + applyElements[i]);
                                item.removeClass('bfb-state-shown');
                                item.addClass('bfb-state-hidden');
                            }
                        break;

                    case 'hf':
                            var applyElements = row['apply_field'];
                            for (var i = 0; i < applyElements.length; i++) {
                                var item = $('.' + applyElements[i]);
                                item.removeClass('bfb-state-hidden');
                                item.addClass('bfb-state-shown');
                            }
                        break;

                    case 'svo':

                        break;
                }
            }
        },

        ajaxSubmit: function(form) {
            var self             = this;
            var elements         = $(form).find('input, select, textarea').not('.bfb-state-hidden input, .bfb-state-hidden select, .bfb-state-hidden textarea');
            var submitData       = elements.serialize();
            var disabledElements = $(form).find('.bfb-state-hidden input, .bfb-state-hidden select, .bfb-state-hidden textarea');
            var skipElements     = [];
            disabledElements.each(function(i, elem) {
                var elemName = $(elem).attr('name');
                if ($.inArray($(elem).attr('name'), skipElements)=== -1) {
                    // skipElements.push($(elem).attr('name'));
                    // submitData += '&' + elemName + '=bfbdisabled';
                }
            });

            $.ajax({
                url: form.attr('action'),
                data: submitData,
                type: 'post',
                dataType: 'json',
                beforeSend: function() {
                    $(form).trigger('bfbBeforeSubmit', submitData);
                    self.element.parent().addClass('bfb-loading');
                    if (self.options.beforeJsSelector) {
                        eval($(self.options.beforeJsSelector).html());
                    }
                },
                success: function(res) {
                    self.element.parent().removeClass('bfb-loading');
                    $(form).trigger('bfbAfterSubmit', res);

                    if (res.message && res.type == 'alert') {
                        alert(res.message);
                        return;
                    }

                    if (res.status && res.key) {
                        $.ajax({
                            url: self.options.successUrl,
                            data: {key: res.key, submission_id: self.options.submissionId},
                            type: 'post'
                        });
                    }

                    if (res.status && res.message) {
                        if (self.options.afterJsSelector) {
                            eval($(self.options.afterJsSelector).html());
                        }
                        var parent = self.element.parent();
                        parent.html(res.message);

                        if (res.redirect) {
                            setTimeout(function() {
                                window.location.href = res.redirect;
                            }, 500);
                        } else {
                            parent.find("script").each(function(i) {
                                eval($(this).html());
                            });
                        }

                    } else {
                        if (typeof(grecaptcha) === 'object' && self.element.find('.mgz-element-bfb_recaptcha').length) {
                            grecaptcha.reset();
                        }
                    }
                }
            });
        },

        loadElementValidation: function (elem, loaded) {
            var uniq       = $(elem).attr('bfb-uniq');
            var self       = this;
            var conditions = this.logic[uniq];
            if (conditions) {
                _.each(conditions, function(row) {
                    if (self.validateConditions(row['conditions'], row['aggregator'])) {
                        self.applyActions(row['actions']);
                    } else {
                        self.revertState(row['actions']);
                    }
                });
            }
        },

        prepareSections: function (str) {
            var sections = [];
            var values = [];
            var count  = (str.match(/\[/g) || []).length;
            var pos    = 0;
            for (var i = 0; i < count; i++) {
                pos          = str.indexOf('[', pos);
                var pos2     = str.indexOf(']', pos);
                var tmpValue = str;
                if (pos !== 1 && pos2 !== 1 && (pos2>pos)) {
                    var values = tmpValue.substr(pos, (pos2-pos+2));
                    values     = values.replace('[', '');
                    values     = values.replace(']', '');
                    values     = values.split(".");
                    if ($.inArray(values[0], sections) === -1) {
                        sections.push(values[0]);
                    }
                }
                pos++;
            }
            return sections;
        },

        loadData: function() {
            var sections = [];
            var self     = this;

            if (typeof(BFB_PRODUCT_ID) !== 'undefined') {
                this.element.find('input[name="product_id"]').val(BFB_PRODUCT_ID);
            }

            // Elements
            this.element.find('.bfb-control').each(function(index, el) {
                var value = $(this).data('value') ? $(this).data('value') + '' : '';
                if (value && (value.indexOf('[') !== -1) && (value.indexOf(']') !== -1)) {
                    sections = sections.concat(self.prepareSections(value));
                }
            });

            // Dynamic Variables
            this.element.find('.bfb-dynamic-variables').each(function(index, el) {
                sections = sections.concat($(this).html());
            });

            this.ajaxLoadData(sections);
        },

        ajaxLoadData: function(sections) {
            var self = this;
            var data = {};

            data['sections']   = sections;
            data['key']        = this.options.key;
            if (this.options.submissionHash) {
                data['submission'] = this.options.submissionHash;
            }

            if ($('body').hasClass('catalog-product-view')) {
                var classes = $('body').attr('class').split(" ");
                for (var i = 0; i < classes.length; i++) {
                    if (classes[i].startsWith('product-')) {
                        data['urlkey'] = classes[i].replace('product-', '');
                        break;
                    }
                }
            }
            data['qs'] = window.location.search;

            $.ajax({
                url: self.options.loadDataUrl,
                data: data,
                success: function(res) {

                    var sections = res.sections;

                    if (sections) {
                        // Elements
                        self.element.find('.bfb-control').each(function(index, el) {
                            if ($(this).is('input') || $(this).is('textarea')) {
                                var value = $(this).data('value') ? $(this).data('value') + '' : '';
                                if (value && (value.indexOf('[') !== -1) && (value.indexOf(']') !== -1)) {
                                    for (var section in sections) {
                                        for (var attribute in sections[section]) {
                                            var dynVari = '\\[' + section + '.' + attribute + '\\]';
                                            var regex   = new RegExp(dynVari, "g");
                                            value       = value.replace(regex, sections[section][attribute]);
                                            $(this).val(value);
                                        };
                                    };
                                }
                                var newValue = $(this).val();
                                newValue     = self.processPageVariables(newValue);
                                newValue     = newValue.replace(/\s*\[.*?\]\s*/g , " ");
                                if (!$(this).hasClass('bfb-value-loaded')) {
                                    $(this).val(newValue);
                                    $(this).trigger('change');
                                    $(this).removeAttr('data-value');
                                }
                            }
                        });

                        // Dynamic Variables
                        self.element.find('.bfb-dynamic-variables').each(function(index, el) {
                            var _html = $(this).html();
                            if (_html && (_html.indexOf('[') !== -1) && (_html.indexOf(']') !== -1)) {
                                for (var section in sections) {
                                    for (var attribute in sections[section]) {
                                        var dynVari = '\\[' + section + '.' + attribute + '\\]';
                                        var regex   = new RegExp(dynVari, "g");
                                        _html       = _html.replace(regex, sections[section][attribute]);
                                        $(this).html(_html);
                                    };
                                };
                            }
                            var newHtml = $(this).html();
                            newHtml     = self.processPageVariables(newHtml);
                            newHtml     = newHtml.replace(/\s*\[.*?\]\s*/g , " ");
                            if (newHtml) {
                                $(this).html(newHtml).removeClass('mgz-hidden');
                            } else {
                                $(this).remove();
                            }
                        });
                    }

                    if (res.data) {
                        var updatedElements = [], childElements = [];
                        var values, elems = [];
                        for (var key in res.data) {
                            values = {};
                            if ($.isArray(res.data[key]) || _.isObject(res.data[key])) {
                                values = res.data[key];
                            } else {
                                values = [];
                                values.push(res.data[key]);
                            }

                            elems = self.element.find('*[name="' + key + '"]');
                            if (!elems.length) elems = self.element.find('*[name="' + key + '[]"]');
                            self.prepareValues(elems, values);

                            // Matrix Table & Time Picker
                            if (!elems.length) {
                                // Radio
                                for (var key2 in values) {
                                    elems = self.element.find('*[name="' + key + '[' + key2 + ']"]');
                                    var newValues = [values[key2]];
                                    self.prepareValues(elems, newValues);
                                }

                                // Checkbox
                                if (!elems.length) {
                                    for (var key2 in values) {
                                        childElements = self.element.find('*[name="' + key + '[' + key2 + '][]"]');
                                        elems = $.merge(elems, childElements);
                                        self.prepareValues(childElements, values[key2]);
                                    }
                                }
                            }

                            updatedElements = $.merge(updatedElements, elems);
                        }
                        self.elems.each(function(index, el) {
                            if ($(this).is(':checkbox') && $.inArray(this, updatedElements)===-1 && $(this).is(':checked')) {
                                if ($(this).is(':checked')) $(this).trigger('click');
                            }
                        });
                    }
                    self.element.parent().removeClass('bfb-loading');
                    
                    self.initConditional();
                    self.initSaveProgressListeners();
                }
            });
        },

        prepareValues: function(elems, values) {
            elems.each(function(index, el) {
                if ($(this).is(':checkbox') || $(this).is(':radio')) {
                    if ($.inArray($(this).val(), values)!==-1) {
                        // Checked
                        if (!$(this).is(':checked')) $(this).trigger('click');
                    } else {
                        // UnChecked
                        if ($(this).is(':checked')) $(this).trigger('click');
                    }
                } else {
                    $(this).val(values).trigger('change');
                }
            });
        },

        processPageVariables: function(val) {
            if (val) {
                var pageUrlKeyRegex = new RegExp('\\[page.url_key\\]', 'g');
                val = val.replace(pageUrlKeyRegex , window.location.href);
                var pageTitleRegex = new RegExp('\\[page.title\\]', 'g');
                val = val.replace(pageTitleRegex , $(document).attr('title'));
            }
            return val;
        },

        saveProgress: function() {
            var self = this;
            var url  = this.options.saveProgressUrl;
            var key  = this.options.key;
            var post = this.getFormValues();
            if (url && key) {
                $.ajax({
                    url: url,
                    data: {key: key, post: self.element.serialize()},
                    type: 'post',
                    dataType: 'json',
                    success: function(res) {
                        
                    }
                });
            }
        }
    });

    return $.bfb.form;
});