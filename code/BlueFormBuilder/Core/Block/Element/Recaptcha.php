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

class Recaptcha extends Control
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context         
     * @param \Magento\Customer\Model\Session                  $customerSession 
     * @param array                                            $data            
     */
    public function __construct(
         \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        $form = $this->getGlobalData('form');
        $element = $this->getElement();
    	if (($element->getData('recaptcha_hide_logged_in') && $this->customerSession->isLoggedIn()) || $form->getEnableRecaptcha()) {
    		return false;
    	}
    	return parent::isEnabled();
    }
}