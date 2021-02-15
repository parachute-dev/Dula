<?php
namespace Parachute\Common\Observer;

use Magento\Framework\View\Page\Config as PageConfig;
use Magento\Framework\Event\ObserverInterface;

class AddBodyClass implements ObserverInterface
{
    protected $pageConfig;
    protected $_storeManager;

    public function __construct(
        PageConfig $pageConfig, 
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->pageConfig = $pageConfig;
        $this->_storeManager = $storeManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $site_code = $this->_storeManager->getWebsite()->getCode();
        $bodyClass = '';
        
        if($site_code == 'store')
        {
            $bodyClass = 'store-consumer';
        }
        elseif($site_code == 'trade')
        {
            $bodyClass = 'store-trade';
        }
        else
        {
            $bodyClass = 'store-consumer';
        }

        $this->pageConfig->addBodyClass($bodyClass);   
    }
}