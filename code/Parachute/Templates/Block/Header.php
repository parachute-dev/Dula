<?php
/**
 * Parachute
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Parachute
 * @package     Parachute_CatalogPlugin
 * @copyright   Copyright (c) Parachute (https://www.thisisparachute.com/)
 */
namespace Parachute\Templates\Block;

use Magento\Catalog\Block\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

class Header extends \Magento\Framework\View\Element\Template
{
    // Fields
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $_response;

    // Constructor
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) 
    {
        // Pass params to base constructor
        parent::__construct(
            $context,
            $data
        );

        // Setup class
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
        $this->_response = $response;

        // We want to make sure only Trade users can view the Trade site unless we're on:
        // - Trade Home
        // - Create Account
        // - Login

        if(
            $this->_storeManager->getWebsite()->getCode() == 'trade' &&
            !$this->_customerSession->isLoggedIn() &&
            $this->getRequest()->getFullActionName() != 'cms_index_index' &&
            $this->getRequest()->getFullActionName() != 'customer_account_login' &&
            $this->getRequest()->getFullActionName() != 'customer_account_create' &&
            $this->getRequest()->getFullActionName() != 'customer_account_forgotpassword' &&
            $this->getRequest()->getFullActionName() != 'customer_account_createpassword' &&            
            $this->getRequest()->getFullActionName() != 'customer_account_confirm'
        )
        {
            $this->_response->setRedirect('/trade/customer/account/login');
        }
    }

    // Methods
    /**
     * Retrieve the block's instance of the Store Manager.
     * 
     * @return \Magento\Store\Model\StoreManagerInterface
     */
    public function getStoreManager() {
        return $this->_storeManager;
    }

    /**
     * Retrieve the block's instance of the Customer Session.
     * 
     * @return \Magento\Customer\Model\Session
     */
    public function getCustomerSession() {
        return $this->_customerSession;
    }
}