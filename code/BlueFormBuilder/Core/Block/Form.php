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

class Form extends \Magento\Framework\View\Element\Template
{
    /**
     * @var string
     */
    protected $_template = 'BlueFormBuilder_Core::form/view.phtml';

    /**
     * @var BlueFormBuilder\Core\Model\Form
     */
    protected $_form;

    /**
     * @var \Magento\Framework\App\Http\Context
     */
    protected $httpContext;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @var \Magezon\Builder\Helper\Data
     */
    protected $builderHelper;

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
     * @param \Magento\Framework\Registry                      $coreRegistry  
     * @param \Magezon\Core\Helper\Data                        $coreHelper    
     * @param \Magezon\Builder\Helper\Data                     $builderHelper 
     * @param \BlueFormBuilder\Core\Helper\Data                $dataHelper    
     * @param \BlueFormBuilder\Core\Helper\Form                $formHelper    
     * @param array                                            $data          
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Framework\Registry $coreRegistry,
        \Magezon\Core\Helper\Data $coreHelper,
        \Magezon\Builder\Helper\Data $builderHelper,
        \BlueFormBuilder\Core\Helper\Data $dataHelper,
        \BlueFormBuilder\Core\Helper\Form $formHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->httpContext   = $httpContext;
        $this->coreRegistry  = $coreRegistry;
        $this->coreHelper    = $coreHelper;
        $this->builderHelper = $builderHelper;
        $this->dataHelper    = $dataHelper;
        $this->formHelper    = $formHelper;
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
     * Escape a string for the HTML attribute context
     *
     * @param string $string
     * @param boolean $escapeSingleQuote
     * @return string
     * @since 100.2.0
     */
    public function escapeHtmlAttr($string, $escapeSingleQuote = true)
    {
        return $this->_escaper->escapeHtmlAttr($string, $escapeSingleQuote);
    }

    public function _toHtml()
    {
        if (!$this->dataHelper->isEnabled()) return;

        $form   = $this->getCurrentForm();
        $formId = $this->getData('form_id');

        if (!$form) {
            if ($formId) {
                $form = $this->formHelper->loadForm($formId);
            } else if ($code = $this->getData('code')) {
                $form = $this->formHelper->loadForm($code);
            }
            $this->setCurrentForm($form);
        }

        if ($form && $form->getId()) {
            return parent::_toHtml();
        }
        return;
    }

    /**
     * @param \BlueFormBuilder\Core\Model\Form $form
     */
    public function setCurrentForm($form)
    {
        $this->_form = $form;
        return $this;
    }

    /**
     * Get current form
     *
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getCurrentForm()
    {
        return $this->_form;
    }

    /**
     * Retrieve current product model
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        return $this->coreRegistry->registry('product');
    }

    /**
     * Get current form
     *
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getCurrentSubmission()
    {
        return $this->coreRegistry->registry('current_submission');
    }

    /**
     * @return string
     */
    public function getProfileHtml()
    {
        $form  = $this->getCurrentForm();
        $profile = str_replace(',"enable_cache":true', '', $form->getData('profile'));
        $block = $this->builderHelper->prepareProfileBlock(\Magezon\Builder\Block\Profile::class, $profile);
        $block->addGlobalData('form', $form);
        return $block->toHtml();
    }

    /**
     * @return array
     */
    public function getFormElements()
    {
        $result   = [];
        $form     = $this->getCurrentForm();
        $elements = $form->getElements();
        foreach ($elements as $element) {
            $result[] = [
                'name' => $element->getElemName(),
                'id'   => $element->getElemId()
            ];
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getMageScript()
    {
        $element        = $this->getElement();
        $form           = $this->getCurrentForm();
        $formId         = $form->getHtmlId();
        $jsBeforeSubmit = $form->getJsBeforeSubmit();
        $jsAfterSubmit  = $form->getJsAfterSubmit();
        $submission     = $this->getCurrentSubmission();
        $id             = $form->getRandomId();
        $result['BlueFormBuilder_Core/js/form'] = [
            'formElements'       => $this->getFormElements(),
            'validCurrentPage'   => true,
            'ajaxLoadSectionUrl' => $this->getUrl('blueformbuilder/section/load'),
            'beforeJsSelector'   => $jsBeforeSubmit ? ".bfb-form-" . $id . "beforesubmit" : '',
            'afterJsSelector'    => $jsAfterSubmit ? ".bfb-form-" . $id . "aftersubmit" : '',
            'successUrl'         => $this->getUrl('blueformbuilder/form/success'),
            'submissionId'       => $submission ? $submission->getId() : '',
            'submissionHash'     => $submission ? $submission->getSubmissionHash() : '',
            'reportUrl'          => $this->getUrl('blueformbuilder/form/ajax'),
            'key'                => $form->getBfbFormKey(),
            'loadDataUrl'        => $this->getUrl('blueformbuilder/form/loadData'),
            'saveProgressUrl'    => $this->getUrl('blueformbuilder/form/saveProgress'),
            'formId'             => $formId,
            'saveFormProgress'   => $form->getEnableAutosave() ? true : false
        ];
        return $result;
    }
}
