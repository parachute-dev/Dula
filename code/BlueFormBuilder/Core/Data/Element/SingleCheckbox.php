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

class SingleCheckbox extends AbstractElement
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
                    'checkbox',
                    [
                        'sortOrder'       => 20,
                        'key'             => self::FIELD_DEFAULT_VALUE,
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Checked by default')
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
                    'sub_label',
                    'text',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'sub_label',
                        'templateOptions' => [
                            'label' => __('Sub Label')
                        ]
                    ]
                );

                $container2->addChildren(
                    'sub_label_font_weight',
                    'text',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'sub_label_font_weight',
                        'templateOptions' => [
                            'label' => __('Sub Label Font Weight')
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
                    'checked_value',
                    'text',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'checked_value',
                        'defaultValue'    => 'Checked',
                        'templateOptions' => [
                            'label' => __('Checked Value')
                        ]
                    ]
                );

                $container3->addChildren(
                    'unchecked_value',
                    'text',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'unchecked_value',
                        'defaultValue'    => 'Unchecked',
                        'templateOptions' => [
                            'label' => __('Unchecked Value')
                        ]
                    ]
                );


        return $advanced;
    }
}