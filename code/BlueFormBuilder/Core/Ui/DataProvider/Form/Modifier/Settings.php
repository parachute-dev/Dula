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

namespace BlueFormBuilder\Core\Ui\DataProvider\Form\Modifier;

use Magento\Ui\Component\Form\Fieldset;
use Magezon\UiBuilder\Data\Form\Element\Factory;
use Magezon\UiBuilder\Data\Form\Element\CollectionFactory;
use Magento\Framework\UrlInterface;

class Settings extends AbstractModifier
{
	const GROUP_SETTINGS                    = 'settings';
	const GROUP_SETTINGS_DEFAULT_SORT_ORDER = 200;

	/**
	 * @var UrlInterface
	 */
	protected $urlBuilder;

	/**
	 * @var \Magento\Cms\Model\Page\Source\PageLayout
	 */
	protected $pageLayout;

	/**
	 * @var \BlueFormBuilder\Core\Model\Source\CustomerGroup
	 */
	protected $customerGroup;

    /**
     * @param Factory                                          $factoryElement    
     * @param CollectionFactory                                $factoryCollection 
     * @param \Magento\Framework\Registry                      $registry          
     * @param UrlInterface                                     $urlBuilder        
     * @param \Magento\Cms\Model\Page\Source\PageLayout        $pageLayout        
     * @param \BlueFormBuilder\Core\Model\Source\CustomerGroup $customerGroup     
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        \Magento\Framework\Registry $registry,
        UrlInterface $urlBuilder,
        \Magento\Cms\Model\Page\Source\PageLayout $pageLayout,
        \BlueFormBuilder\Core\Model\Source\CustomerGroup $customerGroup,
        \Magento\Cms\Ui\Component\Listing\Column\Cms\Options $stores,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \BlueFormBuilder\Core\Helper\Data $dataHelper
    ) {
    	parent::__construct($factoryElement, $factoryCollection, $registry);
        $this->urlBuilder    = $urlBuilder;
        $this->pageLayout    = $pageLayout;
        $this->customerGroup = $customerGroup;
        $this->stores        = $stores;
        $this->storeManager  = $storeManager;
        $this->dataHelper    = $dataHelper;
        $this->setGroup(self::GROUP_SETTINGS);
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        $this->prepareChildren();

        $this->createPanel();

        return $this->meta;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyData(array $data)
    {
        $data['disable_multiple'] = (isset($data['disable_multiple'])) ? (int)$data['disable_multiple'] : 0;
        return $data;
    }

	/**
     * Create Editor panel
     *
     * @return $this
     * @since 101.0.0
     */
    protected function createPanel()
    {
    	$children = $this->getChildren();
        $this->meta = array_replace_recursive(
            $this->meta,
            [
                static::GROUP_SETTINGS => [
                    'arguments' => [
                        'data' => [
                            'config' => [
								'label'                           => __('Settings'),
								'componentType'                   => Fieldset::NAME,
								'collapsible'                     => true,
								'initializeFieldsetDataByDefault' => false,
								'sortOrder'                       => static::GROUP_SETTINGS_DEFAULT_SORT_ORDER
                            ]
                        ]
                    ],
                    'children' => $children
                ]
            ]
        );
        return $this;
    }

    /**
     * @return \Magezon\UiBuilder\Data\Form\Element\Fieldset
     */
    public function prepareChildren()
    {
        $this->buildGeneralFieldset();
        $this->buildCustomerGroupFieldset();
        if (!$this->storeManager->isSingleStoreMode()) {
            $this->buildStoreFieldset();
        }
        $this->buildCustomCssFieldset();
        $this->buildSeoFieldset();
        $this->buildAdvancedFieldset();
    }

    public function buildGeneralFieldset() {
    	$general = $this->addFieldset(
            'general',
            [
				'label'       => __('General'),
				'sortOrder'   => 0,
				'collapsible' => true,
				'dataScope'   => 'data'
            ]
        );

            $general->addChildren(
                'name',
                'text',
                [
					'label'      => __('Form Name'),
					'sortOrder'  => 100,
					'validation' => [
						'required-entry' => true
                    ]
                ]
            );

            $general->addChildren(
                'is_active',
                'boolean',
                [
					'label'     => __('Enable Form'),
					'sortOrder' => 200,
					'default'   => 1
                ]
            );

            $defaultRnableRecaptcha = ($this->dataHelper->getConfig('recaptcha3/public_key') && $this->dataHelper->getConfig('recaptcha3/secret_key'));

            $general->addChildren(
                'enable_recaptcha',
                'boolean',
                [
                    'label'     => __('Enable reCaptcha3'),
                    'sortOrder' => 250,
                    'default'   => $defaultRnableRecaptcha
                ]
            );

            $general->addChildren(
                'disable_form_page',
                'boolean',
                [
					'label'     => __('Disable Form Page'),
					'sortOrder' => 300,
					'default'   => 0
                ]
            );

            $general->addChildren(
                'show_toplink',
                'boolean',
                [
					'label'     => __('Show in Top Links'),
					'sortOrder' => 400
                ]
            );

            $general->addChildren(
                'position',
                'text',
                [
                    'label'     => __('Position'),
                    'sortOrder' => 500,
                    'validation' => [
                        'validate-number'            => true,
                        'validate-greater-than-zero' => true
                    ]
                ]
            );

        return $general;
    }

    public function buildStoreFieldset() {
    	$group = $this->addFieldset(
            'websites',
            [
				'label'       => __('Form in Websites'),
				'sortOrder'   => 300,
				'collapsible' => true,
				'dataScope'   => 'data'
            ]
        );

            $group->addChildren(
                'store_id',
                'multiselect',
                [
                    'sortOrder'    => 100,
                    'options'      => $this->stores->toOptionArray(),
                    'defaultValue' => 0,
                    'validation'   => [
                        'required-entry' => true
                    ],
                    'additionalInfo' => '<a href="https://blog.magezon.com/create-multi-language-form-blue-form-builder?utm_campaign=bfb&utm_source=userguide&utm_medium=backend" target="_blank">How to create multi-language forms with Blue Form Builder?</a>'
                ]
            );

        return $group;
    }

    public function buildCustomerGroupFieldset() {
        $group = $this->addFieldset(
            'customer_groups',
            [
                'label'       => __('Customer Groups'),
                'sortOrder'   => 300,
                'collapsible' => true,
                'dataScope'   => 'data',
                'validation'   => [
                    'required-entry' => true
                ]
            ]
        );

            $group->addChildren(
                'customer_group_id',
                'multiselect',
                [
                    'sortOrder'  => 100,
                    'options'    => $this->customerGroup->toOptionArray(),
                    'validation' => [
                        'required-entry' => true
                    ]
                ]
            );

        return $group;
    }

    public function buildSeoFieldset() {
    	$seo = $this->addFieldset(
            'seo',
            [
				'label'       => __('Search Engine Optimization'),
				'sortOrder'   => 400,
				'collapsible' => true,
				'dataScope'   => 'data'
            ]
        );

        	$seo->addChildren(
                'identifier',
                'text',
                [
                    'label'      => __('URL Key'),
                    'sortOrder'  => 100,
                    'validation' => [
                        'required-entry' => true
                    ]
                ]
            );

            $seo->addChildren(
                'meta_title',
                'text',
                [
					'label'     => __('Meta Title'),
					'sortOrder' => 200
                ]
            );

            $seo->addChildren(
                'meta_description',
                'textarea',
                [
					'label'     => __('Meta Description'),
					'sortOrder' => 300
                ]
            );

            $seo->addChildren(
                'meta_keywords',
                'textarea',
                [
					'label'     => __('Meta Keywords'),
					'sortOrder' => 400
                ]
            );

        return $seo;
    }

    public function buildAdvancedFieldset() {
        $form = $this->getCurrentForm();
    	$advanced = $this->addFieldset(
            'advanced',
            [
				'label'       => __('Advanced'),
				'sortOrder'   => 1000,
				'collapsible' => true,
				'dataScope'   => 'data'
            ]
        );

            $advanced->addChildren(
                'width',
                'text',
                [
                    'label'     => __('Width'),
                    'sortOrder' => 10
                ]
            );

            $advanced->addChildren(
                'enable_autosave',
                'boolean',
                [
                    'label'     => __('Auto Save Form Process'),
                    'sortOrder' => 20,
                    'default'   => 1
                ]
            );

            $advanced->addChildren(
                'disable_multiple',
                'boolean',
                [
                    'label'     => __('Disable multiple submissions from same device'),
                    'sortOrder' => 30,
                    'default'   => 0
                ]
            );

            $advanced->addChildren(
                'disable_multiple_condition',
                'select',
                [
                    'label'     => __('Disable Condition'),
                    'sortOrder' => 40,
                    'options'   => $this->getDisableOptions(),
                    'imports'   => [
                        'visible' => '${ $.provider }:${ $.parentScope }.disable_multiple'
                    ],
                    'value' => 'ip_address',
                    'groupsConfig' => [
                        'form_fields' => [
                            'disable_multiple_fields'
                        ]
                    ]
                ]
            );

            $advanced->addChildren('disable_multiple_fields', 'select', [
                'label'        => __('Form Fields'),
                'component'    => 'BlueFormBuilder_Core/js/form/builder-fields',
                'sortOrder'    => 50,
                'filterType'   => 'condition',
                'disableLabel' => true,
                'multiple'     => true,
                'selectedPlaceholders' => [
                    'defaultPlaceholder' => __('(field)')
                ],
                'validation' => [
                    'required-entry' => true
                ]
            ]);

            $advanced->addChildren(
                'disable_multiple_message',
                'textarea',
                [
                    'label'     => __('Message when disabled'),
                    'sortOrder' => 60,
                    'default'   => __('You are already submit'),
                    'imports'   => [
                        'visible' => '${ $.provider }:${ $.parentScope }.disable_multiple'
                    ]
                ]
            );

            $advanced->addChildren(
                'disable_after_nos',
                'text',
                [
                    'label'      => __('Disable form when it reaches X submissions'),
                    'sortOrder'  => 70,
                    'validation' => [
                        'validate-number'            => true,
                        'validate-greater-than-zero' => true
                    ],
                    'notice' => __('Current submission counter: %1', $form->getSubmissionCount())
                ]
            );

            $advanced->addChildren(
                'redirect_to',
                'text',
                [
                    'label'     => __('Redirect on Submit'),
                    'sortOrder' => 100,
                    'notice'    => __('Use "/" to stay on the same page after submitting.'),
                    'default'   => '/'
                ]
            );

            $advanced->addChildren(
                'redirect_delay',
                'text',
                [
					'label'      => __('Redirect X seconds after form submit'),
					'sortOrder'  => 200,
					'validation' => [
						'validate-number' => true,
						'validate-zero-or-greater' => true
                    ]
                ]
            );

            $advanced->addChildren(
                'submission_prefix',
                'text',
                [
					'label'     => __('Submission Prefix'),
					'sortOrder' => 300,
					'notice'    => __('Ex: BFB => BFB000000001')
                ]
            );

            $advanced->addChildren(
                'page_layout',
                'select',
                    [
                    'label'     => __('Layout'),
                    'sortOrder' => 400,
                    'options'   => $this->pageLayout->toOptionArray(),
                    'value'     => '1column'
                ]
            );

            $content = '<div class="admin__field">
        <div class="admin__field-control" style="margin-left: calc( (100%) * 0.25 );">
            <div class="admin__actions-switch" data-role="switcher">
                <button type="button"class="bfb-btn action-basic" data-index="exportfile" onclick="setLocation(\'' . $this->getExportFileUrl()  . '\')">
                    <span data-bind="text: title">EXPORT FORM FILE</span>
                </button>
                <p>You can import this form template on any other Magento2 site with the extension installed.</p>
            </div>
        </div>';
        
        // if ($submissionCount = $this->getCurrentForm()->getSubmissionCount()) {
        //     $content .= '<div class="admin__field-control" style="margin-left: calc( (100%) * 0.25 );">
        //         <div class="admin__actions-switch" data-role="switcher">
        //             <button type="button"class="bfb-btn action-basic" data-index="exportsubmission" onclick="setLocation(\'' . $this->getExportSubmissionUrl()  . '\')">
        //                 <span data-bind="text: title">EXPORT SUBMISSIONS (' . $submissionCount . ')</span>
        //             </button>
        //         </div>
        //     </div>';
        // }

        $content .= '</div>';

	    	$advanced->addContainer(
	            'shortcode1',
	            [
                    'sortOrder'         => 1000,
                    'label'             => '',
                    'template'          =>'ui/form/components/complex',
                    'content'           => $content,
                    'additionalClasses' => 'bfb-customer-notice'
	            ]
	        );

        return $advanced;
    }

    public function buildCustomCssFieldset() {
        $css = $this->addFieldset(
            'custom_css',
            [
                'label'       => __('Custom CSS'),
                'sortOrder'   => 650,
                'collapsible' => true,
                'dataScope'   => 'data'
            ]
        );

            $css->addChildren(
                'custom_classes',
                'text',
                [
                    'label'     => __('Custom Classes'),
                    'sortOrder' => 100,
                    'note'      => 'Example: bfb-box-shadow'
                ]
            );

            $css->addChildren(
                'custom_css',
                'code',
                [
                    'label'     => __('Custom CSS'),
                    'mode'      => 'css',
                    'sortOrder' => 200
                ]
            );

        return $css;
    }

    protected function getExportFileUrl()
    {
    	return $this->urlBuilder->getUrl('*/*/exportFile', ['id' => $this->getCurrentForm()->getId()]);
    }

    protected function getExportSubmissionUrl() {
        return $this->urlBuilder->getUrl('*/*/exportSubmission', ['id' => $this->getCurrentForm()->getId()]);
    }

    /**
     * @return array
     */
    public function getDisableOptions()
    {
        return [
            [
                'label' => 'Customer ID',
                'value' => 'customer_id'
            ],
            [
                'label' => 'Ip Address',
                'value' => 'ip_address'
            ],
            [
                'label' => 'Form Fields',
                'value' => 'form_fields'
            ]
        ];
    }
}