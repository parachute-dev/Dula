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

class SingleSlider extends AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareAdvancedTab();
    	return $this;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareAdvancedTab()
    {
        $advanced = $this->addTab(
            'tab_advanced',
            [
                'sortOrder'       => 50,
                'templateOptions' => [
                    'label' => __('Advanced')
                ]
            ]
        );

	        $container1 = $advanced->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 10
                ]
            );

            	$container1->addChildren(
	                self::FIELD_REQUIRED,
	                'toggle',
	                [
						'sortOrder'       => 10,
						'key'             => self::FIELD_REQUIRED,
						'templateOptions' => [
	                        'label' => __('Do not accept minimum value')
	                    ]
	                ]
	            );

            	$container1->addChildren(
	                'min',
	                'number',
	                [
                        'sortOrder'       => 20,
                        'key'             => 'min',
                        'defaultValue'    => 0,
                        'templateOptions' => [
	                        'label' => __('Min')
	                    ]
	                ]
	            );

                $container1->addChildren(
                    'max',
                    'number',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'max',
                        'defaultValue'    => 100,
                        'templateOptions' => [
                            'label' => __('Max')
                        ]
                    ]
                );

            $container2 = $advanced->addContainerGroup(
                'container2',
                [
                    'sortOrder' => 20
                ]
            );

                $container2->addChildren(
                    'step',
                    'number',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'step',
                        'defaultValue'    => 1,
                        'templateOptions' => [
                            'label' => __('Step')
                        ]
                    ]
                );

                $container2->addChildren(
                    'skin',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'skin',
                        'defaultValue'    => 'modern',
                        'templateOptions' => [
                            'label'   => __('Skin'),
                            'options' => $this->getSkins()
                        ]
                    ]
                );

                $container2->addChildren(
                    'color',
                    'color',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'color',
                        'defaultValue'    => '#007cbe',
                        'templateOptions' => [
                            'label' => __('Slider Color')
                        ]
                    ]
                );

            $container3 = $advanced->addContainerGroup(
                'container3',
                [
                    'sortOrder' => 30
                ]
            );

                $container3->addChildren(
                    'prefix',
                    'text',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'prefix',
                        'templateOptions' => [
                            'label' => __('Prefix')
                        ]
                    ]
                );

                $container3->addChildren(
                    'postfix',
                    'text',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'postfix',
                        'templateOptions' => [
                            'label' => __('Postfix')
                        ]
                    ]
                );

                $container3->addChildren(
                    'default_value',
                    'number',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'default_value',
                        'defaultValue'    => 0,
                        'templateOptions' => [
                            'label' => __('Default')
                        ]
                    ]
                );

        return $advanced;
    }

    /**
     * @return array
     */
    public function getSkins()
    {
        return [
            [
                'label' => __('Flat UI'),
                'value' => 'flat'
            ],
            [
                'label' => __('Modern'),
                'value' => 'modern'
            ],
            [
                'label' => __('HTML5'),
                'value' => 'html5'
            ],
            [
                'label' => __('Nice White'),
                'value' => 'nice'
            ],
            [
                'label' => __('Simple Dark'),
                'value' => 'simple'
            ]
        ];
    }
}