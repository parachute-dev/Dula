<?php

namespace Meetanshi\RestrictProductQuantity\Helper;

use Magento\Checkout\Model\Cart;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Customer\Model\Session;

class Data extends AbstractHelper
{
    const ENABLE_DISABLE = 'restrictproductquantity/general/enable_disable';
    const LIMIT_PRODUCT_QTY_MSG = 'restrictproductquantity/general/error_message';
    const LOGIN_REQUIRED = 'restrictproductquantity/general/login_required';
    private $cart;
    private $productRepo;
    private $customerSession;

    public function __construct(
        Cart $cart,
        ProductRepository $productRepo,
        Session $customerSession,
        Context $context
    ) {
        parent::__construct($context);
        $this->cart = $cart;
        $this->productRepo = $productRepo;
        $this->customerSession = $customerSession;
    }

    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            self::ENABLE_DISABLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getCart()
    {
        return $this->cart;
    }

    public function getProductById($id)
    {
        return $this->productRepo->getById($id);
    }
    public function getProductBySku($sku)
    {
        return $this->productRepo->get($sku);
    }

    public function getCustomerSession()
    {
        return $this->customerSession;
    }

    public function getLimitAttributeCode()
    {
        return 'limit_product_quantity';
    }

    public function isLoginRequired()
    {
        return $this->scopeConfig->getValue(
            self::LOGIN_REQUIRED,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getLimitProductQtyMsg()
    {
        $ret = $this->scopeConfig->getValue(
            self::LIMIT_PRODUCT_QTY_MSG,
            ScopeInterface::SCOPE_STORE
        );
        return ($ret) ? $ret : 'For product @product only @limit can be bought per account.';
    }
}
