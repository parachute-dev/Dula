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

class Address extends AbstractElement
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
                    'show_address1',
                    'toggle',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'show_address1',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show Address1')
                        ]
                    ]
                );

                $container1->addChildren(
                    'show_address2',
                    'toggle',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'show_address2',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show Address2')
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
                    'show_city',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'show_city',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show City')
                        ]
                    ]
                );

                $container2->addChildren(
                    'show_state',
                    'toggle',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'show_state',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show State')
                        ]
                    ]
                );

                $container2->addChildren(
                    'show_zip',
                    'toggle',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'show_zip',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show Zip Code')
                        ]
                    ]
                );

            $advanced->addChildren(
                'show_country',
                'toggle',
                [
                    'sortOrder'       => 30,
                    'key'             => 'show_country',
                    'defaultValue'    => true,
                    'templateOptions' => [
                        'label' => __('Show Country')
                    ]
                ]
            );

        return $advanced;
    }
}