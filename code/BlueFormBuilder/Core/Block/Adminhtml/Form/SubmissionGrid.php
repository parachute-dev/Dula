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

namespace BlueFormBuilder\Core\Block\Adminhtml\Form;

class SubmissionGrid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $systemStore;

    /**
     * @var \BlueFormBuilder\Core\Model\Form\Submission\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context                       $context           
     * @param \Magento\Backend\Helper\Data                                  $backendHelper     
     * @param \Magento\Framework\Registry                                   $registry          
     * @param SystemStore                                                   $systemStore       
     * @param \BlueFormBuilder\Core\Model\Form\Submission\CollectionFactory $collectionFactory 
     * @param array                                                         $data              
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\System\Store $systemStore,
        \BlueFormBuilder\Core\Model\Form\Submission\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->registry          = $registry;
        $this->systemStore       = $systemStore;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getCurrentForm()
    {
    	if ($this->getData('current_form')) {
    		return $this->getData('current_form');
    	}
    	return $this->registry->registry('current_form');
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('submissionGrid');
        $this->setDefaultSort('submission_id');
        $this->setDefaultDir('DESC');
        $this->setUseAjax(true);
    }

    /**
     * @return Store
     */
    protected function _getStore()
    {
        $storeId = (int)$this->getRequest()->getParam('store', 0);
        return $this->_storeManager->getStore($storeId);
    }

    /**
     * @return $this
     */
    protected function _prepareCollection()
    {
    	$collection = $this->collectionFactory->create();
    	$this->setCollection($collection);
        parent::_prepareCollection();
    	return $this;
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'action',
            [
                'header'           => __('Action'),
                'type'             => 'action',
                'header_css_class' => 'data-grid-actions-cell',
                'column_css_class' => 'a-center',
                'sortable'         => false,
                'filter'           => false,
                'is_system'        => true,
                'renderer'         => 'BlueFormBuilder\Core\Block\Adminhtml\Form\Submission\Field\Renderer\Action',
                'header_css_class' => '_fit bfb-submission-grid-action',
                'column_css_class' => 'a-center bfb-submission-grid-action'
            ]
        );

        $this->addColumn(
            'submission_id',
            [
				'header'           => __('ID'),
				'index'            => 'submission_id',
				'header_css_class' => '_fit bfb-submission-grid-increment_id',
				'column_css_class' => 'bfb-submission-grid-increment_id'
            ]
        );

        $this->addColumn(
            'increment_id',
            [
                'header'           => __('Increment ID'),
                'index'            => 'increment_id',
                'header_css_class' => '_fit bfb-submission-grid-increment_id',
                'column_css_class' => 'bfb-submission-grid-increment_id'
            ]
        );

        $form = $this->getCurrentForm();
		$elememts = $form->getElements();
		foreach ($elememts as $element) {
			$elemName       = $element->getElemName();
			$builderElement = $element->getBuilderElement();
			$grid           = $builderElement->getData('grid');

			if (isset($grid['header_css_class'])) {
				$grid['header_css_class'] .= ' bfb-submission-grid-' . $elemName;
			} else {
				$grid['header_css_class'] = 'bfb-submission-grid-' . $elemName;
			}

			if (isset($grid['column_css_class'])) {
				$grid['column_css_class'] .= ' bfb-submission-grid-' . $elemName;
			} else {
				$grid['column_css_class'] = 'bfb-submission-grid-' . $elemName;
			}

			$config = [];
			$config = [
                'header' => $element->getConfig('label'),
                'index'  => $elemName
            ];

            if ($grid) $config = array_merge_recursive($config, $grid);

            if (isset($config['type']) && $config['type'] == 'number' && !isset($config['renderer'])) {
                $config['renderer'] = 'BlueFormBuilder\Core\Block\Adminhtml\Form\Submission\Field\Renderer\Number';
            }

			$this->addColumn($elemName, $config);
		}

        $storeCollection = $this->systemStore->getStoreCollection();
        $stores = [];
        foreach ($storeCollection as $store) {
            $name = $store->getName();
            if ($store->getId() && $name) {
	            $stores[$store->getId()] = $name;
	        }
        }

        $this->addColumn(
            'bfb_customer_name',
            [
				'header'           => __('Customer Name'),
				'index'            => 'bfb_customer_name',
				'header_css_class' => 'bfb-submission-grid-bfb_customer_name',
				'column_css_class' => 'bfb-submission-grid-bfb_customer_name'
            ]
        );

        $this->addColumn(
            'bfb_customer_email',
            [
				'header'           => __('Customer Email'),
				'index'            => 'bfb_customer_email',
				'header_css_class' => 'bfb-submission-grid-bfb_customer_email',
				'column_css_class' => 'bfb-submission-grid-bfb_customer_email'
            ]
        );

        $this->addColumn(
            'store_id',
            [
				'header'           => __('Store'),
				'index'            => 'store_id',
				'type'             => 'options',
				'options'          => $stores,
				'header_css_class' => 'bfb-submission-grid-store_id',
				'column_css_class' => 'bfb-submission-grid-store_id'
            ]
        );

        $this->addColumn(
            'creation_time',
            [
                'header'   => __('Created'),
                'index'    => 'creation_time',
                'type'     => 'date',
                'sortable' => false,
                'filter'   => false
            ]
        );

        $this->addExportType('blueformbuilder/form/exportSubmissionCsv', __('CSV'));
        $this->addExportType('blueformbuilder/form/exportSubmissionExcel', __('Excel XML'));

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('blueformbuilder/form/submissionGrid', [
			'_current' => true
        ]);
    }
}