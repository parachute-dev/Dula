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

class Time extends AbstractElement
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
                    'min_hour',
                    'select',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'min_hour',
                        'defaultValue'    => 0,
                        'templateOptions' => [
                            'label'   => __('Min Hour'),
                            'options' => $this->getRange(0, 23, 1, true)
                        ]
                    ]
                );

                $container1->addChildren(
                    'max_hour',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'max_hour',
                        'defaultValue'    => 23,
                        'templateOptions' => [
                            'label'   => __('Max Hour'),
                            'options' => $this->getRange(0, 23, 1,true)
                        ]
                    ]
                );

                $container1->addChildren(
                    'hour_step',
                    'select',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'hour_step',
                        'defaultValue'    => 1,
                        'templateOptions' => [
                            'label'   => __('Hour Step'),
                            'options' => $this->getRange(1, 24)
                        ]
                    ]
                );

            $container2 = $validation->addContainerGroup(
                'container2',
                [
                    'sortOrder' => 20
                ]
            );

                $container2->addChildren(
                    'min_minute',
                    'select',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'min_minute',
                        'defaultValue'    => 0,
                        'templateOptions' => [
                            'label'   => __('Min Minute'),
                            'options' => $this->getRange(0, 59, 1, true)
                        ]
                    ]
                );

                $container2->addChildren(
                    'max_minute',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'max_minute',
                        'defaultValue'    => 59,
                        'templateOptions' => [
                            'label'   => __('Max Minute'),
                            'options' => $this->getRange(0, 59, 1, true)
                        ]
                    ]
                );

                $container2->addChildren(
                    'minute_step',
                    'select',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'minute_step',
                        'defaultValue'    => 10,
                        'templateOptions' => [
                            'label'   => __('Minute Step'),
                            'options' => $this->getRange(1, 60)
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
                    'hide_am_pm',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'hide_am_pm',
                        'templateOptions' => [
                            'label' => __('Hide AM / PM')
                        ]
                    ]
                );

                $container1->addChildren(
                    'default_hour',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'default_hour',
                        'defaultValue'    => 8,
                        'templateOptions' => [
                            'label'   => __('Default Hour'),
                            'options' => $this->getRange(0, 23, 1, true)
                        ]
                    ]
                );

                $container1->addChildren(
                    'default_minute',
                    'select',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'default_minute',
                        'defaultValue'    => 0,
                        'templateOptions' => [
                            'label'   => __('Default Minute'),
                            'options' => $this->getRange(0, 59, 1, true)
                        ]
                    ]
                );

            $advanced->addChildren(
                self::FIELD_AUTOTOFOCUS,
                'toggle',
                [
                    'sortOrder'       => 20,
                    'key'             => self::FIELD_AUTOTOFOCUS,
                    'templateOptions' => [
                        'label'        => __('Autofocus'),
                        'tooltipClass' => 'tooltip-bottom tooltip-bottom-right',
                        'tooltip'      => __('When present, it specifies that the element should automatically get focus when the page loads.')
                    ]
                ]
            );

        return $advanced;
    }
}