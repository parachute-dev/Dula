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

class Subscribe extends AbstractElement
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
                            'label' => __('Required Field')
                        ]
                    ]
                );

                $container1->addChildren(
                    self::FIELD_DEFAULT_VALUE,
                    'toggle',
                    [
                        'sortOrder'       => 20,
                        'key'             => self::FIELD_DEFAULT_VALUE,
                        'templateOptions' => [
                            'label' => __('Checked by default')
                        ]
                    ]
                );

                $container1->addChildren(
                    'sub_label',
                    'text',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'sub_label',
                        'className'       => 'mgz-width200',
                        'templateOptions' => [
                            'label' => __('Sub Label')
                        ]
                    ]
                );

            $advanced->addChildren(
                'subscribe_fields',
                'uiSelect',
                [
                    'sortOrder'       => 20,
                    'key'             => 'subscribe_fields',
                    'templateOptions' => [
                        'multiple' => true,
                        'element'  => 'BlueFormBuilder_Core/js/modal/element/subscribe-fields',
                        'label'    => __('Add field value to the Newsletter List')
                    ]
                ]
            );

            $container2 = $advanced->addContainerGroup(
                'container2',
                [
                    'sortOrder' => 30
                ]
            );

                $container2->addChildren(
                    'send_email',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'send_email',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Send Success Email')
                        ]
                    ]
                );

                $container2->addChildren(
                    'confirm',
                    'toggle',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'confirm',
                        'templateOptions' => [
                            'label' => __('Need to Confirm')
                        ]
                    ]
                );

        return $advanced;
    }

    public function getDefaultValues()
    {
        return [
            'label' => 'Subscribe to Our Newsletter'
        ];
    }
}