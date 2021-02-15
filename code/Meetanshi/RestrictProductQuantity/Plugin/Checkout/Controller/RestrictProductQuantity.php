<?php

namespace Meetanshi\RestrictProductQuantity\Plugin\Checkout\Controller;

use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlFactory;
use Meetanshi\RestrictProductQuantity\Helper\Data;
use Magento\Checkout\Controller\Index\Index;
use Magento\Sales\Model\Order;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;

class RestrictProductQuantity
{
    private $urlModel;
    private $helper;
    private $resultRedirectFactory;
    private $messageManager;
    private $customerSession;
    private $orderModel;
    protected $_catalogProductTypeConfigurable;


    public function __construct(
        UrlFactory $urlFactory,
        Data $helper,
        Session $customerSession,
        Order $orderModel,
        RedirectFactory $redirectFactory,
        Configurable $catalogProductTypeConfigurable,
        ManagerInterface $messageManager
    )
    {
        $this->urlModel = $urlFactory;
        $this->customerSession = $customerSession;
        $this->orderModel = $orderModel;
        $this->resultRedirectFactory = $redirectFactory;
        $this->helper = $helper;
        $this->messageManager = $messageManager;
        $this->_catalogProductTypeConfigurable = $catalogProductTypeConfigurable;
    }

    public function aroundExecute(
        Index $subject,
        \Closure $proceed
    )
    {
        $this->urlModel = $this->urlModel->create();
        $canProceed = true;
        if ($this->helper->isEnabled()) {
            $customerSession = $this->helper->getCustomerSession();

            if ($this->helper->isLoginRequired() && !$customerSession->isLoggedIn()) {
                $canProceed = false;
                $this->messageManager->addErrorMessage(__('Customer must be logged in to place order.'));
            } else {
                $items = $this->helper->getCart()->getQuote()->getAllItems();
                foreach ($items as $item) {
                    $type = $item->getProductType();
                    if ($type == 'bundle' || $type == 'configurable' || $type == 'grouped') {
                        continue;
                    } else {
                        $productId = (int)$item->getProductId();
                        $product = $this->helper->getProductById($productId);
                        $sku = $item->getSku();
                        $totalOrderedQty = 0;

                        if ($customerSession->isLoggedIn()) {
                            $customerId = $customerSession->getCustomerId();
                            $orders = $this->orderModel->getCollection()
                                ->addFieldToFilter("customer_id", $customerId);
                            if (!empty($orders)) {
                                foreach ($orders as $order) {
                                    foreach ($order->getAllItems() as $orderItem) {
                                        $orderProductSku = $orderItem->getSku();
                                        if ($sku == $orderProductSku) {
                                            $totalOrderedQty += (int)$orderItem->getQtyOrdered();
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        $limitQty = (int)$product->getData($this->helper->getLimitAttributeCode());
                        if ($limitQty != null && $limitQty > 0) {
                            $cartQty = (int)$item->getQty();
                            $totalOrderedQty += $cartQty;
                        }

                        if ($limitQty != null && $limitQty > 0) {
                            if ($totalOrderedQty > $limitQty) {
                                $canProceed = false;
                                $msg = $this->helper->getLimitProductQtyMsg();
                                $msg = str_replace("@product", '"' . $product->getName() . '"', $msg);
                                $msg = str_replace("@limit", $limitQty, $msg);
                                $this->messageManager->addErrorMessage(__($msg));
                            }
                        }
                    }
                }
            }
            if (!$canProceed) {
                $defaultUrl = $this->urlModel->getUrl('*/cart', ['_secure' => true]);
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setUrl($defaultUrl);
            }
        }
        return $proceed();
    }
}
