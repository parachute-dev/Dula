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

namespace BlueFormBuilder\Core\Model\Element;

class Subscribe extends YesNoElement
{
    /**
     * @var \Magento\Newsletter\Model\SubscriberFactory
     */
    protected $_subscriberFactory;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @param \Magezon\Builder\Data\Elements              $builderElements   
     * @param \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory 
     * @param \Magezon\Core\Helper\Data                   $coreHelper        
     * @param array                                       $data              
     */
    public function __construct(
        \Magezon\Builder\Data\Elements $builderElements,
        \Magento\Newsletter\Model\SubscriberFactory $subscriberFactory,
        \Magezon\Core\Helper\Data $coreHelper,
        array $data = []
    ) {
        parent::__construct($builderElements, $data);
		$this->_subscriberFactory = $subscriberFactory;
		$this->coreHelper         = $coreHelper;
    }

	public function success()
    {
    	if ($this->getOrigValue()) {
    		$subscribeFields = $this->getConfig('subscribe_fields');
    		if ($subscribeFields) {
				$form       = $this->getForm();
				$submission = $this->getSubmission();
				$params     = $this->coreHelper->unserialize($submission->getPost());
				$emails     = [];
                foreach ($subscribeFields as $_field) {
					$element  = $form->getElement($_field);
					$elemName = $element->getElemName();
                    if (isset($params[$elemName])) {
                    	$email = trim($params[$elemName]);
                    	if (filter_var($email, FILTER_VALIDATE_EMAIL) && !in_array($email, $emails)) {
                    		$subscriber = $this->_subscriberFactory->create();
                            $subscriber->setImportMode(true);
                            $subscriber->subscribe($email);
                            if ($this->getConfig('confirm')) {
                                $subscriber->setStatus(\Magento\Newsletter\Model\Subscriber::STATUS_NOT_ACTIVE)->save();
                                $subscriber->setImportMode(false);
                                $subscriber->sendConfirmationRequestEmail();
                            } else {
                                $subscriber->setStatus(\Magento\Newsletter\Model\Subscriber::STATUS_SUBSCRIBED)->save();
                                $subscriber->setImportMode(false);
                                if ($this->getConfig('send_email')) {
                                    $subscriber->sendConfirmationSuccessEmail();
                                }
                            }
                    	}
                    }
                }
            }
    	}
    }

    public function prepareValue($val)
    {
        $value = $val;
        if ($val) {
            $value = __('Yes');
        } else {
            $value = __('No');
        }
        $this->setValue($value);
        $this->setHtmlValue($value);
        $this->setEmailHtmlValue($value);
    }
}