<?php
/**
 * @category Mageants StoreViewPricing
 * @package Mageants_StoreViewPricing
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\StoreViewPricing\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\Http;

/**
 * Save Price class
 */
class Productview implements ObserverInterface
{
    /**
     * request
     *
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * pricing
     *
     * @var \Mageants\StoreViewPricing\Model\Pricing
     */
    private $pricing;

    /**
     * message
     *
     * @var Magento\Framework\Message\ManagerInterface
     */
    private $message;

    /**
     * helper
     *
     * @var Mageants\StoreViewPricing\Helper\Data
     */
    private $helper;

    private $storeManager;

    public function __construct(
        Http $request,
        \Mageants\StoreViewPricing\Model\Pricing $pricing,
        \Magento\Framework\Message\ManagerInterface $message,
        \Mageants\StoreViewPricing\Helper\Data $helper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->request = $request;
        $this->_pricing = $pricing;
        $this->_message=$message;
        $this->_helper =$helper;
        $this->jsonHelper = $jsonHelper;
        $this->_storeManager = $storeManager;
    }
    /**
     * Execute and perform price for store view
     */
    // @codingStandardsIgnoreLine
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ((int)$this->_helper->priceScope()==2) {
            $storeId = $this->_storeManager->getStore()->getId();
            $productData = $this->_pricing->getCollection()
            ->addFieldToFilter(
                'store_id',
                $storeId
            )->addFieldToFilter(
                'entity_id',
                $observer->getProduct()->getId()
            );
            if (empty($productData->getData())) {
                $productData = $this->_pricing->getCollection()
                ->addFieldToFilter(
                    'store_id',
                    0
                )->addFieldToFilter(
                    'entity_id',
                    $observer->getProduct()->getId()
                );
            }

            if ($productData->getData()) {
                foreach ($productData as $pData) {
                    $observer->getProduct()->setPrice($pData->getPrice());
                    $observer->getProduct()->setSpecialPrice($pData->getSpecialPrice());
                    $observer->getProduct()->setSpecialFromDate($pData->getSpecialFromDate());
                    $observer->getProduct()->setSpecialToDate($pData->getSpecialToDate());
                    $observer->getProduct()->setCost($pData->getCost());
                    $observer->getProduct()->setMsrpDisplayActualPriceType($pData->getMsrpDisplayActualPriceType());
                    $observer->getProduct()->setMsrp($pData->getMsrp());
                    
                    $tierPrices=null;
                    if ($pData->getTierPrice()) {
                        if ($this->jsonValidator($pData->getTierPrice())) {
                            $tierPrices = $this->jsonHelper->jsonDecode($pData->getTierPrice());
                        }
                    }
                    
                    $storeviewprice=[];
                    if ($tierPrices) {
                        foreach ($tierPrices as $tierprice) {
                            if (array_key_exists('delete', $tierprice)) {
                                continue;
                            }
                            $storeviewprice[]=$tierprice;
                        }
                    }
                    $observer->getProduct()->setTierPrice($storeviewprice);
                }
            }
        }
    }

    private function jsonValidator($data = null)
    {
        // @codingStandardsIgnoreStart
        if (!empty($data)) {
            @json_decode($data);

            return (json_last_error() === JSON_ERROR_NONE);
        }
        // @codingStandardsIgnoreEnd
        return false;
    }
}
