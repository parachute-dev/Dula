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

namespace BlueFormBuilder\Core\Block\Form;

class View extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'BlueFormBuilder_Core::form/view.phtml';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context  
     * @param \Magento\Framework\Registry                      $registry 
     * @param array                                            $data     
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_coreRegistry = $registry;
    }

    public function _toHtml()
    {
        $form = $this->getCurrentForm();
        $block = $this->getLayout()->createBlock(\BlueFormBuilder\Core\Block\Form::class)
        ->setCurrentForm($form)
        ->setFormId($form->getId());

        if ($this->getCurrentSubmission()) {
            $block->setData('cache_lifetime', null);
        }
        return $block->toHtml();
    }

    /**
     * Get current form
     *
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getCurrentForm()
    {
        return $this->_coreRegistry->registry('current_form');
    }

    /**
     * Get current form
     *
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getCurrentSubmission()
    {
        return $this->_coreRegistry->registry('current_submission');
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->_addBreadcrumbs();

        $form = $this->getCurrentForm();
        if ($form) {
            $title = $form->getMetaTitle();
            if (!$title) {
                $title = $form->getName();
            }
            $this->pageConfig->getTitle()->set($title);
            
            $description = $form->getMetaDescription();
            if ($description) {
                $this->pageConfig->setDescription($description);
            }
            $keywords = $form->getMetaKeywords();
            if ($keywords) {
                $this->pageConfig->setKeywords($keywords);
            }
            
            $this->pageConfig->addRemotePageAsset(
                $form->getUrl(),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );

            $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
            if ($pageMainTitle) {
                $pageMainTitle->setPageTitle($form->getName());
            }
        }

        return $this;
    }

    protected function _addBreadcrumbs()
    {
        $this->getLayout()->createBlock(\Magento\Catalog\Block\Breadcrumbs::class);
        $form             = $this->getCurrentForm();
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');

        if ($breadcrumbsBlock) {
            $breadcrumbsBlock->addCrumb(
                'form',
                [
                    'label' => $form->getName(),
                    'title' => $form->getName()
                ]
            );
        }
    }
}
