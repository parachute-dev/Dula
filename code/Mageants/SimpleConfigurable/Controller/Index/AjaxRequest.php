<?php
/**
 * @category Mageants SimpleConfigurable
 * @package Mageants_SimpleConfigurable
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\SimpleConfigurable\Controller\Index;

class AjaxRequest extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    ) {
        $this->_registry = $registry;
        $this->_pageFactory = $pageFactory;
        $this->_productloader = $_productloader;
        $this->_scopeConfig = $scopeConfig;
        return parent::__construct($context);
    }
    public function execute()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORES;
        $enable = $this->_scopeConfig->getValue("SimpleConfigurable_config/SimpleConfigurable_settings/enable", $storeScope);
        $showqty_enable = $this->_scopeConfig->getValue("SimpleConfigurable_config/SimpleConfigurable_settings/showqty", $storeScope);
        if ($enable == 1 && $showqty_enable == 1) {
            $productid = $this->getRequest()->getParam('id');
            $productdata = $this->_productloader->create()->load($productid);
            $qty = $productdata->getQuantityAndStockStatus();
            $availableqty = $qty['qty'];
            $qtydiv = " <div> Available Quantity : $availableqty </div>";
            $response_array['status'] ="success";
            $response_array['success_message'] =$qtydiv;
        } else {
            $response_array['status'] ="error";
        }
        echo json_encode($response_array);
        exit();
    }
}
