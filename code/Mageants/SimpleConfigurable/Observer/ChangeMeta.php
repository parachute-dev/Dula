<?php

namespace Mageants\SimpleConfigurable\Observer;

class ChangeMeta implements \Magento\Framework\Event\ObserverInterface
{
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		if ($product = $observer->getEvent()->getProduct()) {

			$objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
        	$session = $objectManager->get('Magento\Customer\Model\Session');
        	if($session->getCustomProductId() && $session->getCustomProductId() != ''){
        		$simpleproduct = $objectManager->create('Magento\Catalog\Model\Product')->load($session->getCustomProductId());
            	$product->setMetaTitle($simpleproduct->getMetaTitle());
            	$product->setMetaKeyword($simpleproduct->getMetaKeyword());
            	$product->setMetaDescription($simpleproduct->getMetaDescription());
        	}
        }
	}
}