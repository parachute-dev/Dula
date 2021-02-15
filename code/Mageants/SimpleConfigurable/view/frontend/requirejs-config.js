/**
 * Mageants SimpleConfigurable Magento2 Extension 
 */ 
var config = {
    map: {
        '*': {
        	'jqueryHistory': 'Mageants_SimpleConfigurable/js/jquery.history',
            'mage/SwatchRenderer': 'Magento_Swatches/js/swatch-renderer',
            'Magento_Swatches/js/swatch-renderer': 'Mageants_SimpleConfigurable/js/swatch-renderer',
            'configurable': 'Magento_ConfigurableProduct/js/configurable',
            'Magento_ConfigurableProduct/js/configurable': 'Mageants_SimpleConfigurable/js/configurable'
        },
    }
};
