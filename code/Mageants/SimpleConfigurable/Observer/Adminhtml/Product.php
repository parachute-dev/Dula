<?php
/**
 * @category Mageants SimpleConfigurable
 * @package Mageants_SimpleConfigurable
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\SimpleConfigurable\Observer\Adminhtml;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;

class Product implements ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    public $request;

    /**
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $jsonHelper;

    public function __construct(
    \Magento\Framework\App\RequestInterface $request,
    \Magento\Framework\Json\Helper\Data $jsonHelper
    ){
    $this->request = $request;
    $this->jsonHelper = $jsonHelper;
    }

    public function execute(Observer $observer)
    {
        $pdata= $this->request->getPost("product");
    	if (isset($_REQUEST['configurable-matrix-serialized']) && $_REQUEST['configurable-matrix-serialized']) {
            $product = $observer->getEvent()->getProduct();
    		$simpleProducts= $this->jsonHelper->jsonDecode($_REQUEST['configurable-matrix-serialized']);
    		foreach ($simpleProducts as $simpleProduct) {
    			if(isset($simpleProduct['checked']) && $simpleProduct['checked']){
    				$product->setStoreId($product->getStoreId())->setData('is_default_selected',$simpleProduct['id'])->save();

    			}
    		}
    	}
        return;
    }
}