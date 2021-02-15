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

namespace BlueFormBuilder\Core\Block;

class TopLinks extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'toplink.phtml';

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory
     */
    protected $formCollectionFactory;

    /**
     * @var \BlueFormBuilder\Core\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \BlueFormBuilder\Core\Helper\Form
     */
    protected $formHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context                 $context               
     * @param \Magento\Framework\App\Http\Context                              $httpContext           
     * @param \Magento\Customer\Model\Session                                  $customerSession       
     * @param \Magezon\Core\Helper\Data                                        $coreHelper            
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory 
     * @param \BlueFormBuilder\Core\Helper\Data                                $dataHelper            
     * @param \BlueFormBuilder\Core\Helper\Form                                $formHelper            
     * @param array                                                            $data                  
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Session $customerSession,
        \Magezon\Core\Helper\Data $coreHelper,
        \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory,
        \BlueFormBuilder\Core\Helper\Data $dataHelper,
        \BlueFormBuilder\Core\Helper\Form $formHelper,
        array $data = []
    ) {
        parent::__construct($context);
        $this->httpContext           = $httpContext;
        $this->customerSession       = $customerSession;
        $this->coreHelper            = $coreHelper;
        $this->formCollectionFactory = $formCollectionFactory;
        $this->dataHelper            = $dataHelper;
        $this->formHelper            = $formHelper;
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        if ($this->getData('template')) {
            $this->setTemplate($this->getData('template'));
        }

        $this->addData(
            [
                'cache_lifetime' => 86400,
                'cache_tags'     => [\BlueFormBuilder\Core\Model\Form::CACHE_TAG]
            ]
        );
    }

    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            'BLUEFORMBUILDER_FORM',
            $this->_storeManager->getStore()->getId(),
            (int)$this->_storeManager->getStore()->isCurrentlySecure(),
            $this->_design->getDesignTheme()->getId(),
            $this->getCustomerGroupId(),
            $this->coreHelper->serialize($this->getData()),
            'template' => $this->getTemplate()
        ];
    }

    public function getCustomerGroupId()
    {
        return $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    protected function _toHtml()
    {
        if (!$this->dataHelper->isEnabled()) {
            return;
        }

        return parent::_toHtml();
    }

    /**
     * @return \BlueFormBuilder\Core\Model\ResourceModel\Form\Collection
     */
    public function getCollection()
    {
        $store   = $this->_storeManager->getStore();
        $groupId = $this->customerSession->getCustomerGroupId();

        $collection = $this->formCollectionFactory->create();
        $collection->addFieldToFilter('show_toplink', 1)
        ->addFieldToFilter('disable_form_page', 0)
        ->addIsActiveFilter()
        ->addStoreFilter($store)
        ->addCustomerGroupFilter($groupId)
        ->setOrder('position', 'ASC');

        return $collection;
    }
}
