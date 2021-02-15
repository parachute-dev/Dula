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

class Number extends AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareValidationTab();
    	$this->prepareAdvancedTab();
    	return $this;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareValidationTab()
    {
        $validation = $this->addTab(
            'tab_validation',
            [
                'sortOrder'       => 40,
                'templateOptions' => [
                    'label' => __('Validation')
                ]
            ]
        );

            $container1 = $validation->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 10
                ]
            );

                $container1->addChildren(
                    'min',
                    'number',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'min',
                        'templateOptions' => [
                            'label' => __('Min')
                        ]
                    ]
                );

                $container1->addChildren(
                    'max',
                    'number',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'max',
                        'templateOptions' => [
                            'label' => __('Max')
                        ]
                    ]
                );

                $container1->addChildren(
                    'step',
                    'number',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'step',
                        'defaultValue'    => 1,
                        'templateOptions' => [
                            'label' => __('Step')
                        ]
                    ]
                );

        return $validation;
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
	                        'label' => __('Required Field')
	                    ]
	                ]
	            );

    	    	$container1->addChildren(
    	            self::FIELD_DEFAULT_VALUE,
    	            'number',
    	            [
                        'sortOrder'       => 20,
                        'key'             => self::FIELD_DEFAULT_VALUE,
                        'templateOptions' => [
    	                    'label' => __('Default Value')
    	                ]
    	            ]
    	        );

            $colors = $advanced->addTab(
                'colors',
                [
                    'sortOrder'       => 20,
                    'templateOptions' => [
                        'label' => __('Colors')
                    ]
                ]
            );

                $normal = $colors->addContainerGroup(
                    'normal',
                    [
                        'sortOrder'       => 10,
                        'templateOptions' => [
                            'label' => __('Normal')
                        ]
                    ]
                );

                    $color1 = $normal->addContainerGroup(
                        'color1',
                        [
                            'sortOrder' => 10
                        ]
                    );

                        $color1->addChildren(
                            'btn_color',
                            'color',
                            [
                                'sortOrder'       => 10,
                                'key'             => 'btn_color',
                                'templateOptions' => [
                                    'label' => __('Text Color')
                                ]
                            ]
                        );

                        $color1->addChildren(
                            'btn_background_color',
                            'color',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'btn_background_color',
                                'templateOptions' => [
                                    'label' => __('Background Color')
                                ]
                            ]
                        );

                $hover = $colors->addContainerGroup(
                    'hover',
                    [
                        'sortOrder'       => 20,
                        'templateOptions' => [
                            'label' => __('Hover')
                        ]
                    ]
                );

                    $color2 = $hover->addContainerGroup(
                        'color2',
                        [
                            'sortOrder' => 10
                        ]
                    );

                        $color2->addChildren(
                            'btn_hover_color',
                            'color',
                            [
                                'sortOrder'       => 10,
                                'key'             => 'btn_hover_color',
                                'templateOptions' => [
                                    'label' => __('Text Color')
                                ]
                            ]
                        );

                        $color2->addChildren(
                            'btn_hover_background_color',
                            'color',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'btn_hover_background_color',
                                'templateOptions' => [
                                    'label' => __('Background Color')
                                ]
                            ]
                        );

        return $advanced;
    }
}