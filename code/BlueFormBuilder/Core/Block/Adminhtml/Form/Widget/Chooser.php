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

namespace BlueFormBuilder\Core\Block\Adminhtml\Form\Widget;

class Chooser extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \BlueFormBuilder\Core\Model\FormFactory
     */
    protected $_formFactory;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context                          $context
     * @param \Magento\Backend\Helper\Data                                     $backendHelper
     * @param \BlueFormBuilder\Core\Model\FormFactory                          $formFactory
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory $collectionFactory
     * @param array                                                            $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \BlueFormBuilder\Core\Model\FormFactory $formFactory,
        \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_formFactory       = $formFactory;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Block construction, prepare grid params
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setDefaultSort('form_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
        $this->setDefaultFilter(['chooser_is_active' => '1']);
    }

    /**
     * Prepare chooser element HTML
     *
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element Form Element
     * @return \Magento\Framework\Data\Form\Element\AbstractElement
     */
    public function prepareElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $uniqId    = $this->mathRandom->getUniqueHash($element->getId());
        $sourceUrl = $this->getUrl('blueformbuilder/form_widget/chooser', ['uniq_id' => $uniqId]);

        $chooser = $this->getLayout()->createBlock(
            \Magento\Widget\Block\Adminhtml\Widget\Chooser::class
        )->setElement(
            $element
        )->setConfig(
            $this->getConfig()
        )->setFieldsetId(
            $this->getFieldsetId()
        )->setSourceUrl(
            $sourceUrl
        )->setUniqId(
            $uniqId
        );

        if ($element->getValue()) {
            $collection = $this->_collectionFactory->create();
            $collection->addFieldToFilter("identifier", $element->getValue());
            $form = $collection->getFirstItem();
            if ($form->getId()) {
                $chooser->setLabel($this->escapeHtml($form->getName()));
            }
        }

        $element->setData('after_element_html', $chooser->toHtml());
        return $element;
    }

    /**
     * Grid Row JS Callback
     *
     * @return string
     */
    public function getRowClickCallback()
    {
        $chooserJsObject = $this->getId();
        $js = '
            function (grid, event) {
                var trElement = Event.findElement(event, "tr");
                var blockId = trElement.down("td").innerHTML.replace(/^\s+|\s+$/g,"");
                var blockTitle = trElement.down("td").next().innerHTML;
                ' .
            $chooserJsObject .
            '.setElementValue(blockId);
                ' .
            $chooserJsObject .
            '.setElementLabel(blockTitle);
                ' .
            $chooserJsObject .
            '.close();
            }
        ';
        return $js;
    }

    /**
     * Prepare form collection
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare columns for Cms blocks grid
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'chooser_identifier',
            [
                'header' => __('URL Key'),
                'align'  => 'left',
                'index'  => 'identifier'
            ]
        );

        $this->addColumn(
            'chooser_title',
            [
                'header' => __('Name'),
                'align'  => 'left',
                'index'  => 'name'
            ]
        );

        $this->addColumn(
            'chooser_is_active',
            [
                'header'           => __('Status'),
                'index'            => 'is_active',
                'type'             => 'options',
                'column_css_class' => '_fit',
                'options'          => [
                    0 => __('Disabled'),
                    1 => __('Enabled')
                ]
            ]
        );

        return parent::_prepareColumns();
    }

    /**
     * Get grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('blueformbuilder/form_widget/chooser', ['_current' => true]);
    }
}
