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

namespace BlueFormBuilder\Core\Controller\Form;

class Success extends \Magento\Framework\App\Action\Action
{
	/**
	 * @var array
	 */
	protected $_attachments = [];

	/**
	 * @var \Magezon\Core\Helper\Data
	 */
	protected $coreHelper;

	/**
	 * @var \BlueFormBuilder\Core\Model\EmailNotification
	 */
	protected $emailNotification;

	/**
	 * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory
	 */
	protected $submissionCollectionFactory;

	/**
	 * @param \Magento\Framework\App\Action\Context                                  $context                     
	 * @param \Magezon\Core\Helper\Data                                              $coreHelper                  
	 * @param \BlueFormBuilder\Core\Model\EmailNotification                          $emailNotification           
	 * @param \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory 
	 */
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magezon\Core\Helper\Data $coreHelper,
        \BlueFormBuilder\Core\Model\EmailNotification $emailNotification,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory
    ) {
        parent::__construct($context);
		$this->coreHelper                  = $coreHelper;
		$this->emailNotification           = $emailNotification;
		$this->submissionCollectionFactory = $submissionCollectionFactory;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
    	try {
			$post = $this->getRequest()->getPostValue();
			if (isset($post['key']) && $post['key']) {
				$collection = $this->submissionCollectionFactory->create();
				$collection->addFieldToFilter('submission_hash', $post['key']);
				$submission = $collection->getFirstItem();
				if ($submission->getId() && (!$submission->getIsActive() || $submission->getId()==$post['submission_id'])) {
					$form = $submission->getForm();
					// Before Save
					$post = $this->coreHelper->unserialize($submission->getPost());
	                foreach ($form->getElements() as $element) {
                    	$val = isset($post[$element->getElemName()]) ? $post[$element->getElemName()] : '';
	                    $element->setForm($form);
	                    $element->setOrigValue($val);
	                    $element->prepareValue($val);
	                    $element->beforeSave();
	                }

	                // Prepare Values
	                foreach ($form->getElements() as $element) {
	                    if ($attachments = $element->getAttachments()) {
	                        foreach ($attachments as $_attachment) {
	                            $this->_attachments[] = $_attachment;
	                        }
	                    }
	                }
	                $submission->setAttachments($this->_attachments);

		            $this->emailNotification->setAttachments(
		            	$this->_attachments
		            )->setSubmission(
		                $submission
		            )->sendEmail();

		            // After Save
	                foreach ($form->getElements() as $element) {
	                	$element->setSubmission($submission);
	                    $element->success();
	                }
		            return;
				}
			}
		} catch (\Exception $e) {
		}
    }
}