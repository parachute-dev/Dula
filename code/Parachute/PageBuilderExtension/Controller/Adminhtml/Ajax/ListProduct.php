<?php
/**
 * Parachute
 *
 * @category  Parachute
 * @package   Parachute_CatalogPlugin
 * @copyright Copyright (C) 2019 Parachute (https://www.thisisparachute.com.com)
 */

namespace Parachute\PageBuilderExtension\Controller\Adminhtml\Ajax;

class ListProduct extends \Magento\Backend\App\Action
{
    /**
     * @var \Parachute\PageBuilderExtension\Model\Source\Categories
     */
    protected $products;

    /**
     * @param \Magento\Backend\App\Action\Context      $context    
     * @param \Parachute\PageBuilderExtension\Model\Source\Products $products 
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Parachute\PageBuilderExtension\Model\Source\Products $products
    ) {
        parent::__construct($context);
        $this->products = $products;
    }

    // Gets the list of the Products in the store that match a given name input string
    public function execute()
    {
    	$products = $this->products->getOptions();
    	$this->getResponse()->representJson(
            $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($products)
        );
    	return;
    }
}