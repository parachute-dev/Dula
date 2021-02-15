/**
 * Mageants SimpleConfigurable Magento2 Extension 
 */ 
define([
    'jquery',
    'underscore',
    'jquery/ui',
    'mage/SwatchRenderer',
], function ($, _) {
    'use strict';
    
    /* jQuery load event */
    $(document).ready(function () {
        localStorage.setItem('processed', '');
    });
    var ajaxcall = 0;
    var simpleproductid;
    $.widget('preselect.SwatchRenderer', $.mage.SwatchRenderer, {
        /**
         * Get default options values settings with either URL query parameters
         * @private
         */
        _getSelectedAttributes: function () {
            if (typeof this.options.preSelectedOption !== 'undefined') {
                return this.options.preSelectedOption;
            }
        },

        /**
         * Emulate mouse click on all swatches that should be selected
         * @param {Object} [selectedAttributes]
         * @private
         */
        _EmulateSelected: function (selectedAttributes) {
            var currentURL = window.location.href;
            var simpleProductId = '';
            var selectedOptionId = new Array(); 
            var selectedLabel = '';
            var k = 0, j = 0;
            if (typeof this.options.productUrls !== 'undefined') {
                $.each(this.options.productUrls, function (productId, productUrl) {
                    if (productUrl == currentURL) {
                        simpleProductId = productId;
                        return true;
                    }
                });
            }
            $.each(this.options.jsonConfig.attributes, function () {
                var item = this;
                var allOptions = item.options;
                $.each(allOptions, function (key, optionObj) {
                    var products = optionObj.products;
                    for (var i = 0; i < products.length; i++) {
                        var childProductId = optionObj.products[i];
                        if (simpleProductId === childProductId) {
                           selectedOptionId[j] = optionObj.id;
                           j++;
                        }
                    }
                });
            });
           $.each(selectedAttributes, $.proxy(function (attributeCode, optionId) {
                if (selectedOptionId.length !== 0) {
                    optionId = selectedOptionId[k];
                    k++;
                }
                var el = this.element.find('.' + this.options.classes.attributeClass +
                    '[attribute-code="' + attributeCode + '"] [option-id="' + optionId + '"]');

                if (el.hasClass('selected')) {
                    return;
                }

                this.element.find('.' + this.options.classes.attributeClass
                + '[attribute-code="' + attributeCode + '"] .swatch-select').val(optionId).change();

                var optionLabel =  this.element.find('.' + this.options.classes.attributeClass
                + '[attribute-code="' + attributeCode + '"] .swatch-select option[option-id="' + optionId + '"]').text();

                this.element.find('.' + this.options.classes.attributeClass
                + '[attribute-code="' + attributeCode + '"] .swatch-attribute-selected-option').text(optionLabel);

                this.element.find('.' + this.options.classes.attributeClass
                + '[attribute-code="' + attributeCode + '"] .swatch-option[option-id="' + optionId + '"]').trigger('click');

            }, this));
        },

        /**
         * Change product attributes.
         */
        _ReplaceData: function (simpleProductId, $widget) {
            if (typeof $widget.options.customAttributes[simpleProductId] !== 'undefined') {
                $.each($widget.options.customAttributes[simpleProductId], function (attributeCode, data) {
                    var $block = $(data.class);
                    if (typeof data.replace != 'undefined' && data.replace) {
                        if (data.value == '') {
                            $block.remove();
                        }

                        if ($block.length > 0) {
                            $block.replaceWith(data.value);
                        } else {
                            $(data.container).html(data.value);
                        }
                    } else {
                        if ($block.length > 0) {
                            if($block.selector.includes('meta')){
                                $($block.selector).attr('content', data.value);
                            }
                            else{
                                $block.html(data.value);    
                            }
                        }
                    }
                });
            }
        },
        /**
         * Event for swatch options
         *
         * @param $this
         * @param $widget
         * @private
         */
        _OnClick: function ($this, $widget) {
            /* Fix issue cannot add product to cart */
            var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                $wrapper = $this.parents('.' + $widget.options.classes.attributeOptionsWrapper),
                $label = $parent.find('.' + $widget.options.classes.attributeSelectedOptionLabelClass),
                attributeId = $parent.attr('attribute-id'),

                $input = $parent.find('.' + $widget.options.classes.attributeInput);
            if ($widget.inProductList) {
                $input = $widget.productForm.find(
                    '.' + $widget.options.classes.attributeInput + '[name="super_attribute[' + attributeId + ']"]'
                );
            }

            if ($this.hasClass('disabled')) {
                return;
            }

            if ($this.hasClass('selected')) {
                $parent.removeAttr('option-selected').find('.selected').removeClass('selected');
                $input.val('');
                $label.text('');
                $this.attr('aria-checked', false);
            } else {
                $parent.attr('option-selected', $this.attr('option-id')).find('.selected').removeClass('selected');
                $label.text($this.attr('option-label'));
                $input.val($this.attr('option-id'));
                $input.attr('data-attr-name', this._getAttributeCodeById(attributeId));
                $this.addClass('selected');
                if (typeof $widget._toggleCheckedAttributes !== "undefined") {
                    $widget._toggleCheckedAttributes($this, $wrapper);
                }
            }

            var currentURL = window.location.href;
            var simpleProductId = '';
            if (!localStorage.getItem('processed')) {
                var selectedOptionId = '', selectedLabel = '';
                if (typeof this.options.productUrls !== 'undefined') {
                    $.each(this.options.productUrls, function (productId, productUrl) {
                        if (productUrl == currentURL) {
                            simpleProductId = productId;
                            return true;
                        }
                    });
                }
                if (simpleProductId) {
                    $.each(this.options.jsonConfig.attributes, function () {
                        var item = this;
                        var allOptions = item.options;
                        $.each(allOptions, function (key, optionObj) {
                            var products = optionObj.products;
                            for (var i = 0; i < products.length; i++) {
                                var childProductId = optionObj.products[i];
                                if (simpleProductId === childProductId) {
                                   selectedOptionId = optionObj.id;
                                   selectedLabel = optionObj.label;
                                   var select = $('div[attribute-id="'+ item.id +'"]').find('select');
                                   if (select.find('option').length > 0) {
                                       select.val(selectedOptionId).trigger('change');
                                   } else {
                                        var parent = $('div[attribute-id="'+ item.id +'"]'),
                                        label = parent.find('.' + $widget.options.classes.attributeSelectedOptionLabelClass),
                                        input = parent.find('.' + $widget.options.classes.attributeInput);
                                        parent.removeAttr('option-selected').find('.selected').removeClass('selected');
                                        parent.attr('option-selected', selectedOptionId);
                                        label.text(selectedLabel);
                                        $('input[name="super_attribute['+ item.id +']"]').val(selectedOptionId);
                                        $('.swatch-option[option-id='+selectedOptionId+']').addClass('selected');
                                   }
                                }
                            }
                        });
                    });
                }
            }
            $widget._Rebuild();

            if ($widget.element.parents($widget.options.selectorProduct)
                    .find(this.options.selectorProductPrice).is(':data(mage-priceBox)')
            ) {
                this.options.tierPriceTemplate = $(this.options.tierPriceTemplateSelector).html();  
                $widget._UpdatePrice();
            }
            var products = $widget._CalcProducts();
            if (products.length && !this.options.jsonConfig.doNotReplaceData) {
                if (!simpleProductId) {
                    var simpleProductId = products[0];
                }
                $widget._ReplaceData(simpleProductId, this);
                /* Update input type hidden - fix base image doesn't change when choose option */
                if (simpleProductId && document.getElementsByName('product').length) {
                    document.getElementsByName('product')[0].value = simpleProductId;
                }
                var config = this.options;
                require(['jqueryHistory'], function () {
                    if (config.replaceUrl && typeof config.productUrls[simpleProductId] !== 'undefined') {
                        var url = config.productUrls[simpleProductId];
                        var title = null;
                        if (config.customAttributes[simpleProductId].name.value !== 'undefined') {
                            title = config.customAttributes[simpleProductId].name.value;
                        }
                        History.replaceState(null, title, url);
                    }
                });
            }
            setTimeout(function(){
                $widget._loadMedia();
                $input.trigger('change');
                localStorage.setItem('processed', true);
            }, 1000);
        },
        /* 16-07-2019 */
        getProduct: function () {
            var products = this._CalcProducts();
            simpleproductid ='';
            if(products.length == 1){
                ajaxcall = 1;
                simpleproductid = products;
                this._UpdatePreOrder();  
            }else{
                ajaxcall = 0;
                this._UpdatePreOrder();
            }
            return _.isArray(products) ? products[0] : null;
        },

        _UpdatePreOrder: function () {
            var page_url = $("#url").val();
            if(ajaxcall == 1 && page_url != " "){
                $.ajax({ 
                    type: "POST",
                    showLoader: true, 
                    url: page_url, 
                    data : 'id='+ simpleproductid,
                    dataType: "json", 
                    success: function(data){ 
                        if(data.status == "success"){
                             $('.show_qty').html(data.success_message); 
                        }else{
                            $('.show_qty').html();
                        }
                    }
                });
            }
                 
        },

        _OnChange: function ($this, $widget) {
            /* Fix issue cannot add product to cart */
            $(".swatch-select").closest('div').siblings('.swatch-attribute-selected-option').remove();
            var $parent = $this.parents('.' + $widget.options.classes.attributeClass),
                attributeId = $parent.attr('attribute-id'),
                $input = $parent.find('.' + $widget.options.classes.attributeInput);
            if ($widget.productForm.length > 0) {
                $input = $widget.productForm.find(
                    '.' + $widget.options.classes.attributeInput + '[name="super_attribute[' + attributeId + ']"]'
                );
            }
            /**/

            if ($this.val() > 0) {
                $parent.attr('option-selected', $this.val());
                $input.val($this.val());
            } else {
                $parent.removeAttr('option-selected');
                $input.val('');
            }

            $widget._Rebuild();
            $widget._UpdatePrice();


            var products = $widget._CalcProducts();

            if (products.length && !this.options.jsonConfig.doNotReplaceData) {
                var simpleProductId = products[0];
                /**
                 * Change product attributes.
                 */
                $widget._ReplaceData(simpleProductId, this);
                /**
                 * Update input type hidden - fix base image doesn't change when choose option
                 */
                if (simpleProductId && document.getElementsByName('product').length) {
                    document.getElementsByName('product')[0].value = simpleProductId;
                }

                var config = this.options;
                require(['jqueryHistory'], function () {
                    if (config.replaceUrl && typeof config.productUrls[simpleProductId] !== 'undefined') {
                        var url = config.productUrls[simpleProductId];
                        var title = null;
                        if (config.customAttributes[simpleProductId].name.value !== 'undefined') {
                            title = config.customAttributes[simpleProductId].name.value;
                        }
                        History.replaceState(null, title, url);
                    }
                });
            }

            $widget._loadMedia();
            $input.trigger('change');
        },

        _getAttributeCodeById: function (attributeId) {
            var attribute = this.options.jsonConfig.attributes[attributeId];

            return attribute ? attribute.code : attributeId;
        },

    });

    return $.preselect.SwatchRenderer;
});