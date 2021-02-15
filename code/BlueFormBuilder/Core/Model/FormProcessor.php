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

namespace BlueFormBuilder\Core\Model;

class FormProcessor
{
	/**
	 * @var \Magento\Customer\Model\Visitor
	 */
	protected $customerVisitor;

	/**
	 * @var \Magento\Customer\Model\Session
	 */
	protected $customerSession;

	/**
	 * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
	 */
	protected $remoteAddress;

	/**
	 * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory
	 */
	protected $submissionCollectionFactory;

	/**
	 * @var \Magezon\Core\Helper\Data
	 */
	protected $coreHelper;

	/**
	 * @param \Magento\Customer\Model\Visitor                                        $customerVisitor             
	 * @param \Magento\Customer\Model\Session                                        $customerSession             
	 * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress                   $remoteAddress               
	 * @param \Magento\Framework\App\ResourceConnection                              $resource                    
	 * @param \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory 
	 * @param \Magezon\Core\Helper\Data                                              $coreHelper                  
	 */
	public function __construct(
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
		\Magento\Framework\App\ResourceConnection $resource,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory,
        \Magezon\Core\Helper\Data $coreHelper
	) {
		$this->customerVisitor             = $customerVisitor;
		$this->customerSession             = $customerSession;
		$this->remoteAddress               = $remoteAddress;
		$this->resource                    = $resource;
		$this->submissionCollectionFactory = $submissionCollectionFactory;
		$this->coreHelper                  = $coreHelper;
	}

	public function updateSubmisionValues($form, $submission)
	{
		$profile = $form->getProfile();
        function updateDefaultValue($elements, $elemName, $defaultValue) {
            $newElements = [];
            foreach ($elements as &$_element) {
                if (isset($_element['elem_name']) && $_element['elem_name'] == $elemName) {
                    $_element['default_value'] = $defaultValue;
                }
                if (isset($_element['elements']) && is_array($_element['elements'])) {
                    $_element['elements'] = updateDefaultValue($_element['elements'], $elemName, $defaultValue);
                }
                $newElements[] = $_element;
            }
            return $newElements;
        }
		$post = $this->coreHelper->unserialize($submission->getPost());
        foreach ($post as $elemName => $value) {
            $profile['elements'] = updateDefaultValue($profile['elements'], $elemName, $value);
        }

        $form->setProfile($this->coreHelper->serialize($profile));

        $elements = $form->getElements();
        $exist    = [];
        foreach ($elements as &$element) {
            $config   = $element->getData('config');
            $elemName = $element->getElemName();
            if (isset($config['default_value']) && isset($post[$element->getElemName()]) && !in_array($elemName, $exist)) {
                $config['default_value'] = $post[$element->getElemName()];
                $exist[] = $elemName;
            }
            $element->setData('config', $config);
        }
        $form->setElements($elements);

		return $form;
	}

	/**
	 * @param  \BlueFormBuilder\Core\Model\Form  $form
	 * @return true
	 */
	public function hasSubmitted($form)
	{
		return false;
		$valid = false;
		if ($form->getDisableMultiple()) {
			switch ($form->getDisableMultipleCondition()) {
				case 'customer_id':
						$customerId = $this->customerSession->getCustomerId();
						if ($customerId) {
							$collection = $this->submissionCollectionFactory->create();
							$collection->addFieldToFilter('customer_id', $customerId);
							$collection->addFieldToFilter('form_id', $form->getId());
							if ($collection->getSize()) {
								$valid = true;
							}
						}
					break;
				
				case 'ip_address':
						$remoteId = $this->remoteAddress->getRemoteAddress();
						$collection = $this->submissionCollectionFactory->create();
						$collection->addFieldToFilter('remote_ip', $remoteId);
						$collection->addFieldToFilter('form_id', $form->getId());
						if ($collection->getSize()) {
							$valid = true;
						}
					break;

				case 'form_fields':
					$fields     = $this->coreHelper->unserialize($fields);
                    $collection = $this->submissionCollectionFactory->create();
                    $collection->addFieldToFilter('form_id', $form->getId());
                    $cache      = [];
                    foreach ($collection as $submission) {
                        $valid = false;
                        $params = $this->coreHelper->unserialize($submission->getPost());
                        foreach ($fields as $k2 => $field) {
                            if (
                                !isset($params[$field]) 
                                || !isset($post[$field]) 
                                || (isset($params[$field]) && isset($post[$field]) && (trim($params[$field]) != trim($post[$field])))
                            ) {
                                $valid = true;
                                break;
                            }
                        }
                        if (!$valid) {
                            $result = true;
                            break;
                        }
                    }
					break;
			}
		}
		return $valid;
	}

	public function getFormProgress($formId)
	{	
		$visitorId = $this->customerVisitor->getId();
		$table = $this->resource->getTableName('mgz_blueformbuilder_form_progress');
		$connection = $this->resource->getConnection();
		$select = $connection->select()
		->from($table)
		->where('form_id = ?', $formId)
		->where('visitor_id = ?', $visitorId);
		return $connection->fetchRow($select);
	}

	public function deleteFormProcess($formId)
	{
        $visitorId = $this->customerVisitor->getId();
		$table = $this->resource->getTableName('mgz_blueformbuilder_form_progress');
		$connection = $this->resource->getConnection();
		$select = $connection->delete($table, [
			'form_id = ?' => $formId,
			'visitor_id = ?' => $visitorId
		]);
	}
}