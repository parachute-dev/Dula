<?php

namespace YelHex\SweetTracker\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Checkout\Model\Type\Onepage;
use Magento\Catalog\Model\CategoryFactory;
use YelHex\SweetTracker\Helper\Data;

/**
 * Class SweetTracker
 *
 * @package YelHex\SweetTracker\Block
 * @author Tomas Marcik <tomas@yellowhexagon.com>
 */
class SweetTracker extends \Magento\Framework\View\Element\Template
{

    /** @var Onepage */
    private $onepage;

    /** @var CategoryFactory */
    private $categoryFactory;

    /** @var Data */
    private $helper;

    public function __construct(
        Context $context,
        Onepage $onepage,
        CategoryFactory $categoryFactory,
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->onepage = $onepage;
        $this->categoryFactory = $categoryFactory;
        $this->helper = $helper;
    }

    public function getTrackingId()
    {
        //Check if tracking_id
        $enabled = (bool)$this->helper->getGeneralConfig('enabled');
        $trackingId = $this->helper->getGeneralConfig('tracking_id');

        if ($enabled && $trackingId) {
            return $trackingId;
        }

        return null;
    }

    public function getOrder()
    {
        return $this->onepage->getCheckout()->getLastRealOrder();
    }

    public function isSuccessPage()
    {
        return $this->getRequest()->getFullActionName() == 'checkout_onepage_success';
    }

    public function getCategoryName($categoryId)
    {
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->categoryFactory->create()->load($categoryId);

        if ($category && $category->getId()) {
            return $category->getName();
        }

        return 'Unknown';
    }
}