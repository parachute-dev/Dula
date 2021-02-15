/*jshint browser:true jquery:true*/
/*global alert*/
define(
    [
        'uiComponent'
    ],
    function (Component) {
        "use strict";
        var quoteItemData = window.checkoutConfig.quoteItemData;
        return Component.extend({
            defaults: {
                template: 'Parachute_PageBuilderExtension/summary/item/details'
            },
            quoteItemData: quoteItemData,
            getValue: function(quoteItem) {
                return quoteItem.name;
            },
            getProductSubName: function(quoteItem) {
                var item = this.getItem(quoteItem.item_id);
                console.log(item);
                return item.product_sub_name;
            },
            getItem: function(item_id) {
                var itemElement = null;
                _.each(this.quoteItemData, function(element, index) {
                    if (element.item_id == item_id) {
                        itemElement = element;
                    }
                });
                return itemElement;
            }
        });
    }
);