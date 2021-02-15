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

namespace BlueFormBuilder\Core\Block\Widget;

class Form extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var \BlueFormBuilder\Core\Model\Form
     */
    protected $_form;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @var \BlueFormBuilder\Core\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \BlueFormBuilder\Core\Helper\Form
     */
    protected $formHelper;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context     
     * @param \Magento\Framework\App\Http\Context              $httpContext 
     * @param \Magezon\Core\Helper\Data                        $coreHelper  
     * @param \BlueFormBuilder\Core\Helper\Data                $dataHelper  
     * @param \BlueFormBuilder\Core\Helper\Form                $formHelper  
     * @param array                                            $data        
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magezon\Core\Helper\Data $coreHelper,
        \BlueFormBuilder\Core\Helper\Data $dataHelper,
        \BlueFormBuilder\Core\Helper\Form $formHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->httpContext = $httpContext;
        $this->coreHelper  = $coreHelper;
        $this->dataHelper  = $dataHelper;
        $this->formHelper  = $formHelper;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->setTemplate('widget/form.phtml');
        parent::_construct();
        $this->addData(
            [
                'cache_lifetime' => 86400,
                'cache_tags'     => [\BlueFormBuilder\Core\Model\Form::CACHE_TAG
                ]
            ]
        );
    }

    public function toHtml()
    {
        $form = $this->getForm();
        if (!$form || !$form->getId()) {
            return;
        }
        return parent::toHtml();
    }

    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            'BLUEFORMBUILDER_FORM_WIDGET',
            $this->_storeManager->getStore()->getId(),
            (int)$this->_storeManager->getStore()->isCurrentlySecure(),
            $this->_design->getDesignTheme()->getId(),
            $this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_GROUP),
            $this->coreHelper->serialize($this->getData()),
            'template' => $this->getTemplate()
        ];
    }

    public function getFormHtml()
    {
        $form = $this->getForm();
        if (!$form->getId()) {
            return;
        }
        $block = $this->getLayout()->createBlock('\BlueFormBuilder\Core\Block\Form')
        ->setCode($form->getIdentifier())
        ->setCurrentForm($form);
        return $block->toHtml();
    }

    /**
     * @return BlueFormBuilder\Core\Model\Form
     */
    public function getForm()
    {
        if ($this->_form === null) {
            $this->_form = $this->formHelper->loadForm($this->getData('code'));
        }

        return $this->_form;
    }

    public function setForm($form)
    {
        $this->_form = $form;
        return $this;
    }
}
