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

class Submit extends \Magezon\Builder\Data\Element\AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
        parent::prepareForm();
        $this->prepareButtonDesignTab();
        return $this;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareGeneralTab()
    {
        $general = parent::prepareGeneralTab();

            $general->addChildren(
                'title',
                'text',
                [
                    'sortOrder'       => 10,
                    'key'             => 'title',
                    'defaultValue'    => 'Submit',
                    'templateOptions' => [
                        'label' => __('Text')
                    ]
                ]
            );

            $general->addChildren(
                'show_icon',
                'toggle',
                [
                    'sortOrder'       => 20,
                    'key'             => 'show_icon',
                    'templateOptions' => [
                        'label' => __('Add Icon')
                    ]
                ]
            );

            $container1 = $general->addContainerGroup(
                'container1',
                [
                    'sortOrder'      => 30,
                    'hideExpression' => '!model.show_icon'
                ]
            );

                $container1->addChildren(
                    'icon',
                    'icon',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'icon',
                        'templateOptions' => [
                            'label' => __('Icon')
                        ]
                    ]
                );

                $container1->addChildren(
                    'icon_position',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'icon_position',
                        'defaultValue'    => 'left',
                        'templateOptions' => [
                            'label'   => __('Icon Position'),
                            'options' => $this->getIconPosition()
                        ]
                    ]
                );

        return $general;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareButtonDesignTab()
    {
        $design = $this->addTab(
            'button_design',
            [
                'sortOrder'       => 50,
                'templateOptions' => [
                    'label' => __('Button Design')
                ]
            ]
        );

            $container1 = $design->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 10
                ]
            );

                $container1->addChildren(
                    'button_style',
                    'select',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'button_style',
                        'defaultValue'    => 'flat',
                        'templateOptions' => [
                            'label'   => __('Button Style'),
                            'options' => $this->getButtonStyle()
                        ]
                    ]
                );

                $container1->addChildren(
                    'button_size',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'button_size',
                        'defaultValue'    => 'md',
                        'templateOptions' => [
                            'label'   => __('Button Size'),
                            'options' => $this->getSizeList()
                        ]
                    ]
                );

                $container1->addChildren(
                    'full_width',
                    'toggle',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'full_width',
                        'templateOptions' => [
                            'label' => __('Set Full Width Button')
                        ]
                    ]
                );


            $container2 = $design->addContainerGroup(
                'container2',
                [
                    'sortOrder'      => 20,
                    'hideExpression' => 'model.button_style!="gradient"'
                ]
            );

                $container2->addChildren(
                    'gradient_color_1',
                    'color',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'gradient_color_1',
                        'defaultValue'    => '#dd3333',
                        'templateOptions' => [
                            'label'       => __('Gradient Color 1')
                        ]
                    ]
                );

                $container2->addChildren(
                    'gradient_color_2',
                    'color',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'gradient_color_2',
                        'defaultValue'    => '#eeee22',
                        'templateOptions' => [
                            'label'       => __('Gradient Color 2')
                        ]
                    ]
                );


            $border1 = $design->addContainerGroup(
                'border1',
                [
                    'sortOrder' => 30
                ]
            );

                $border1->addChildren(
                    'button_border_width',
                    'text',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'button_border_width',
                        'templateOptions' => [
                            'label' => __('Border Width')
                        ]
                    ]
                );

                $border1->addChildren(
                    'button_border_radius',
                    'text',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'button_border_radius',
                        'templateOptions' => [
                            'label' => __('Border Radius')
                        ]
                    ]
                );

                $border1->addChildren(
                    'button_border_style',
                    'select',
                    [
                        'key'             => 'button_border_style',
                        'sortOrder'       => 30,
                        'defaultValue'    => 'solid',
                        'templateOptions' => [
                            'label'   => __('Border Style'),
                            'options' => $this->getBorderStyle()
                        ]
                    ]
                );

            $colors = $design->addTab(
                'colors',
                [
                    'sortOrder'       => 40,
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
                            'button_color',
                            'color',
                            [
                                'sortOrder'       => 10,
                                'key'             => 'button_color',
                                'defaultValue'    => '#FFF',
                                'templateOptions' => [
                                    'label' => __('Text Color')
                                ]
                            ]
                        );

                        $color1->addChildren(
                            'button_background_color',
                            'color',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'button_background_color',
                                'defaultValue'    => '#007dbd',
                                'templateOptions' => [
                                    'label' => __('Background Color')
                                ]
                            ]
                        );

                        $color1->addChildren(
                            'button_border_color',
                            'color',
                            [
                                'sortOrder'       => 30,
                                'key'             => 'button_border_color',
                                'defaultValue'    => '#007dbd',
                                'templateOptions' => [
                                    'label' => __('Border Color')
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
                            'button_hover_color',
                            'color',
                            [
                                'sortOrder'       => 10,
                                'key'             => 'button_hover_color',
                                'defaultValue'    => '#fff',
                                'templateOptions' => [
                                    'label' => __('Text Color')
                                ]
                            ]
                        );

                        $color2->addChildren(
                            'button_hover_background_color',
                            'color',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'button_hover_background_color',
                                'defaultValue'    => '#0077b3',
                                'templateOptions' => [
                                    'label' => __('Background Color')
                                ]
                            ]
                        );

                        $color2->addChildren(
                            'button_hover_border_color',
                            'color',
                            [
                                'sortOrder'       => 30,
                                'key'             => 'button_hover_border_color',
                                'defaultValue'    => '#0077b3',
                                'templateOptions' => [
                                    'label' => __('Border Color')
                                ]
                            ]
                        );

            $design->addChildren(
                'button_css',
                'code',
                [
                    'sortOrder'       => 50,
                    'key'             => 'button_css',
                    'templateOptions' => [
                        'label' => __('Inline CSS')
                    ]
                ]
            );

        return $design;
    }

    /**
     * @return array
     */
    public function getButtonStyle()
    {
        return [
            [
                'label' => __('Modern'),
                'value' => 'modern'
            ],
            [
                'label' => __('Flat'),
                'value' => 'flat'
            ],
            [
                'label' => __('Gradient'),
                'value' => 'gradient'
            ]
        ];
    }

    public function getDefaultValues()
    {
        return [
            'button_border_radius' => '2px',
            'button_css'           => 'padding: 10px 20px;'
        ];
    }
}