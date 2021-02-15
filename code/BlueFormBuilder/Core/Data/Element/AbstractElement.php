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

namespace BlueFormBuilder\Core\Data\Element;

abstract class AbstractElement extends \Magezon\Builder\Data\Element\AbstractElement
{
    const FIELD_ELEM_NAME          = 'elem_name';
    const FIELD_LABEL              = 'label';
    const FIELD_EXCLUDE_FROM_EMAIL = 'exclude_from_email';
    const FIELD_EMAIL_LABEL        = 'email_label';
    const FIELD_LABEL_POSITION     = 'label_position';
    const FIELD_LABEL_ALIGNMENT    = 'label_alignment';
    const FIELD_LABEL_WIDTH        = 'label_width';
    const FIELD_CONTROL_WIDTH      = 'control_width';
    const FIELD_TOOLTIP            = 'tooltip';
    const FIELD_DESCRIPTION        = 'description';
    const FIELD_REQUIRED           = 'required';
    const FIELD_PLACEHOLDER        = 'placeholder';
    const FIELD_DEFAULT_VALUE      = 'default_value';
    const FIELD_HIDDEN             = 'hidden';
    const FIELD_READONLY           = 'readonly';
    const FIELD_SHOW_ICON          = 'show_icon';
    const FIELD_ICON_POSITION      = 'icon_position';
    const FIELD_ICON_COLOR         = 'icon_color';
    const FIELD_ICON               = 'icon';
    const FIELD_AUTOTOMPLETE       = 'autocomplete';
    const FIELD_AUTOTOFOCUS        = 'autofocus';

    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareAppearanceTab();
    	return $this;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
	public function prepareAppearanceTab()
    {
    	$element = $this->addTab(
            'tab_appearance',
            [
                'sortOrder'       => 20,
                'templateOptions' => [
                    'label' => __('Appearance')
                ]
            ]
        );

    		$container1 = $element->addContainerGroup(
	            'container1',
	            [
					'sortOrder' => 10
	            ]
	        );

	            $container1->addChildren(
	                self::FIELD_LABEL,
	                'text',
	                [
						'sortOrder'       => 10,
						'key'             => self::FIELD_LABEL,
						'defaultValue'    => $this->getName(),
						'templateOptions' => [
							'label' => __('Label')
	                    ]
	                ]
	            );

                if ($this->getData('control')) {
    	            $container1->addChildren(
    	                self::FIELD_EMAIL_LABEL,
    	                'text',
    	                [
    	                    'sortOrder'       => 20,
    	                    'key'             => self::FIELD_EMAIL_LABEL,
    	                    'templateOptions' => [
    							'label' => __('Email Label')
    	                    ],
    	                    'hideExpression' => 'model.' . self::FIELD_EXCLUDE_FROM_EMAIL
    	                ]
    	            );
                }

    		$container2 = $element->addContainerGroup(
	            'container2',
	            [
					'sortOrder' => 20
	            ]
	        );

	            $container2->addChildren(
	                self::FIELD_LABEL_POSITION,
	                'select',
	                [
						'sortOrder'       => 10,
						'key'             => self::FIELD_LABEL_POSITION,
						'defaultValue'    => 'above',
						'templateOptions' => [
							'label'   => __('Label Position'),
							'options' => $this->getLabelPositionOptions()
	                    ]
	                ]
	            );

                $container2->addChildren(
                    self::FIELD_LABEL_ALIGNMENT,
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => self::FIELD_LABEL_ALIGNMENT,
                        'defaultValue'    => 'left',
                        'templateOptions' => [
                            'label'   => __('Label Alignment'),
                            'options' => $this->getLabelAlignmentOptions()
                        ]
                    ]
                );

            $container3 = $element->addContainerGroup(
                'container3',
                [
                    'sortOrder' => 30
                ]
            );

                $container3->addChildren(
                    self::FIELD_LABEL_WIDTH,
                    'number',
                    [
                        'sortOrder'       => 10,
                        'key'             => self::FIELD_LABEL_WIDTH,
                        'templateOptions' => [
                            'label'       => __('Label Width(percent)'),
                            'placeholder' => '25'
                        ],
                        'hideExpression' => 'model.label_position=="below"||model.label_position=="above"||model.label_position=="hidden"'
                    ]
                );

                $container3->addChildren(
                    self::FIELD_CONTROL_WIDTH,
                    'text',
                    [
                        'sortOrder'       => 20,
                        'key'             => self::FIELD_CONTROL_WIDTH,
                        'templateOptions' => [
                            'label' => __('Control Width'),
                            'note'  => __('Enter "auto" to display default control width')
                        ]
                    ]
                );

            $container4 = $element->addContainerGroup(
                'container4',
                [
                    'sortOrder' => 40
                ]
            );

                $container4->addChildren(
                    self::FIELD_ELEM_NAME,
                    'text',
                    [
                        'sortOrder'       => 10,
                        'key'             => self::FIELD_ELEM_NAME,
                        'templateOptions' => [
                            'label'    => __('Element Name'),
                            'note'     => __('Make sure it is unique.')
                        ]
                    ]
                );

                if ($this->getData('control')) {
    	            $container4->addChildren(
    	                self::FIELD_EXCLUDE_FROM_EMAIL,
    	                'toggle',
    	                [
    	                    'sortOrder'       => 20,
    	                    'key'             => self::FIELD_EXCLUDE_FROM_EMAIL,
    	                    'templateOptions' => [
    							'label' => __('Exclude from Email')
    	                    ]
    	                ]
    	            );
                }

            $element->addChildren(
                self::FIELD_DESCRIPTION,
                'editor',
                [
                    'sortOrder'       => 50,
                    'key'             => self::FIELD_DESCRIPTION,
                    'templateOptions' => [
						'label'   => __('Description'),
						'wysiwyg' => [
							'height' => '50px'
						]
                    ]
                ]
            );

            $element->addChildren(
                self::FIELD_TOOLTIP,
                'editor',
                [
                    'sortOrder'       => 60,
                    'key'             => self::FIELD_TOOLTIP,
                    'templateOptions' => [
						'label'   => __('Tooltip'),
						'wysiwyg' => [
							'height' => '50px'
						]
                    ]
                ]
            );

        return $element;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareIconTab()
    {
        $icon = $this->addTab(
            'tab_icon',
            [
                'sortOrder'       => 30,
                'templateOptions' => [
                    'label' => __('Icon')
                ]
            ]
        );

            $container1 = $icon->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 10
                ]
            );

                $container1->addChildren(
                    self::FIELD_SHOW_ICON,
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => self::FIELD_SHOW_ICON,
                        'templateOptions' => [
                            'label' => __('Show Icon')
                        ]
                    ]
                );

                $container1->addChildren(
                    self::FIELD_ICON_COLOR,
                    'color',
                    [
                        'sortOrder'       => 20,
                        'key'             => self::FIELD_ICON_COLOR,
                        'templateOptions' => [
                            'label' => __('Icon Color')
                        ],
                        'hideExpression' => '!model.' . self::FIELD_SHOW_ICON
                    ]
                );

                $container1->addChildren(
                    self::FIELD_ICON_POSITION,
                    'select',
                    [
                        'sortOrder'       => 30,
                        'key'             => self::FIELD_ICON_POSITION,
                        'defaultValue'    => 'right',
                        'templateOptions' => [
                            'label'   => __('Icon Position'),
                            'options' => $this->getIconPosition()
                        ],
                        'hideExpression' => '!model.' . self::FIELD_SHOW_ICON
                    ]
                );

            $icon->addChildren(
                self::FIELD_ICON,
                'icon',
                [
                    'sortOrder'       => 20,
                    'key'             => self::FIELD_ICON,
                    'templateOptions' => [
                        'label' => __('Icon')
                    ],
                    'hideExpression' => '!model.' . self::FIELD_SHOW_ICON
                ]
            );


        return $icon;
    }

    /**
     * @return array
     */
    protected function getIconPositionOptions()
    {
        return [
            [
                'label' => __('Left'),
                'value' => 'left'
            ],
            [
                'label' => __('Right'),
                'value' => 'right'
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getLabelAlignmentOptions()
    {
        return [
            [
                'label' => __('Left'),
                'value' => 'left'
            ],
            [
                'label' => __('Center'),
                'value' => 'center'
            ],
            [
                'label' => __('Right'),
                'value' => 'right'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getLabelPositionOptions()
    {
        return [
            [
                'label' => __('Above Element'),
                'value' => 'above'
            ],
            [
                'label' => __('Below Element'),
                'value' => 'below'
            ],
            [
                'label' => __('Left of Element'),
                'value' => 'left'
            ],
            [
                'label' => __('Right of Element'),
                'value' => 'right'
            ],
            [
                'label' => __('Hidden'),
                'value' => 'hidden'
            ]
        ];
    }
}