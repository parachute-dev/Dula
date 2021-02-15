<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  BlueFormBuilder
 * @package   BlueFormBuilder_Core
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

namespace BlueFormBuilder\Core\Block\Element;

class Date extends Control
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context 
     * @param \Magento\Framework\Stdlib\DateTime\DateTime      $date    
     * @param array                                            $data    
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        array $data = []
    ) {
        parent::__construct($context, $data);
		$this->_date = $date;
    }

    /**
     * Return offset of current timezone with GMT in seconds
     *
     * @return int
     */
    public function getTimezoneOffsetSeconds()
    {
        return $this->_date->getGmtOffset();
    }

    /**
     * Getter for store timestamp based on store timezone settings
     *
     * @param null|string|bool|int|\Magento\Store\Model\Store $store
     * @return int
     */
    public function getStoreTimestamp($store = null)
    {
        return $this->_localeDate->scopeTimeStamp($store);
    }
}