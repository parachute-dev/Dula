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

class SaveProgress extends \Magento\Framework\App\Action\Action
{
	/**
	 * @var \Magezon\Core\Helper\Data
	 */
	protected $resource;

	/**
	 * @var \Magento\Customer\Model\Visitor
	 */
	protected $customerVisitor;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @var \BlueFormBuilder\Core\Helper\Form
     */
    protected $formHelper;

    /**
     * @param \Magento\Framework\App\Action\Context     $context         
     * @param \Magento\Framework\App\ResourceConnection $resource        
     * @param \Magento\Customer\Model\Visitor           $customerVisitor 
     * @param \Magezon\Core\Helper\Data                 $coreHelper      
     * @param \BlueFormBuilder\Core\Helper\Form         $formHelper      
     */
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Customer\Model\Visitor $customerVisitor,
        \Magezon\Core\Helper\Data $coreHelper,
        \BlueFormBuilder\Core\Helper\Form $formHelper
    ) {
        parent::__construct($context);
        $this->resource        = $resource;
        $this->customerVisitor = $customerVisitor;
        $this->coreHelper      = $coreHelper;
        $this->formHelper      = $formHelper;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
    	$params = $this->getRequest()->getParams();
    	$result['status'] = false;
    	try {
            $formKey = $this->getRequest()->getParam('key');
            $form    = $this->formHelper->loadForm($formKey, 'bfb_form_key');
            if ($form->getId() && isset($params['post']) && $form->getEnableAutosave()) {
                $visitorId  = $this->customerVisitor->getId();
                $post = array();
                parse_str($this->getRequest()->getParam('post'), $post);
                unset($post['bfb_form_key']);
                unset($post['product_id']);
                unset($post['submission_id']);
                $data = [
                    'form_id'    => $form->getId(),
                    'visitor_id' => $visitorId,
                    'post'       => $this->coreHelper->serialize($post)
                ];
                $table = $this->resource->getTableName('mgz_blueformbuilder_form_progress');
                $this->resource->getConnection()->insertOnDuplicate($table, $data);
    			$result['status'] = true;
            }
		} catch (\Exception $e) {
		}
		$this->getResponse()->representJson(
            $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($result)
        );
        return;
    }
}