<?php
namespace Parachute\CatalogPlugin\Plugin\Model;

use Magento\Store\Model\StoreManagerInterface;

class Config
{
    // Fields
    protected $_storeManager;

    // Constructor
    public function __construct(
        StoreManagerInterface $storeManager
    ) 
    {
        $this->_storeManager = $storeManager;
    }

    /**
     * Adding custom options and changing labels
     *
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param [] $options
     * @return []
     */
    public function afterGetAttributeUsedForSortByArray(\Magento\Catalog\Model\Config $catalogConfig, array $options)
    {
        $store = $this->_storeManager->getStore();
        $currencySymbol = $store->getCurrentCurrency()->getCurrencySymbol();

        //Remove specific default sorting options
        unset($options['position']);
        unset($options['name']);
        unset($options['price']);

        // Changing label
        $customOption['position'] = __('Relevance');

        // New sorting options
        $customOption['price_asc'] = __($currencySymbol.' (Low to High)');
        $customOption['price_desc'] = __($currencySymbol.' (High to Low)');
        $customOption['name_asc'] = __('Name (A to Z)');
        $customOption['name_desc'] = __('Name (Z to A)');
        $customOption['created_at'] = __('Newest');

        //Merge default sorting options with custom options
        $options = array_merge($customOption, $options);

        return $options;
    }
}