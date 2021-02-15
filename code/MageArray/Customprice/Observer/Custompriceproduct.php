<?php

namespace MageArray\Customprice\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class Custompriceproduct
 * @package MageArray\Customprice\Observer
 */
class Custompriceproduct implements ObserverInterface
{
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $item = $observer->getEvent()->getData('quote_item');
        $item = ($item->getParentItem() ? $item->getParentItem() : $item);
        $item->setCustomPrice($item->getProduct()->getFinalprice());
        $item->setOriginalCustomPrice($item->getProduct()->getFinalprice());
        $item->getProduct()->setIsSuperMode(true);
    }
}
