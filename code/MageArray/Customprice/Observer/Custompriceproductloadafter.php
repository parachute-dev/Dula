<?php

namespace MageArray\Customprice\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Class Custompriceproductloadafter
 * @package MageArray\Customprice\Observer
 */
class Custompriceproductloadafter implements ObserverInterface
{
    /**
     * @var \MageArray\Customprice\Model\CsvpriceFactory
     */
    protected $_csvpriceFactory;

    /**
     * Custompriceproductloadafter constructor.
     * @param \MageArray\Customprice\Model\CsvpriceFactory $csvpriceFactory
     */
    public function __construct(
        \MageArray\Customprice\Model\ResourceModel\Csvprice $csvpriceFactory
    ) {
        $this->_csvpriceFactory = $csvpriceFactory;
    }

    /**
     * @param Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();

        $csvpriceData = $this->_csvpriceFactory->addCsvFilter($product->getId(), 0);
        if (!empty($csvpriceData)) {
            $csvprice = $csvpriceData['csv_price'];
            if (!empty($csvprice)) {
                $product->setCsvPrice($csvprice);
            } else {
                $product->setCsvPrice('');
            }
        } else {
            $product->setCsvPrice('');
        }
    }
}
