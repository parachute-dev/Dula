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

class StarRatings extends AbstractElement
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

            $advanced->addChildren(
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

            $container1 = $advanced->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 20
                ]
            );

                $container1->addChildren(
                    'number_of_stars',
                    'select',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'number_of_stars',
                        'defaultValue'    => 5,
                        'templateOptions' => [
                            'label'   => __('Number of Stars'),
                            'options' => $this->getRange(1, 10)
                        ]
                    ]
                );

                $container1->addChildren(
                    self::FIELD_DEFAULT_VALUE,
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => self::FIELD_DEFAULT_VALUE,
                        'defaultValue'    => 0,
                        'templateOptions' => [
                            'label'   => __('Default Score'),
                            'options' => $this->getRange(0, 10)
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
                    'star_color',
                    'color',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'star_color',
                        'defaultValue'    => '#c7c7c7',
                        'templateOptions' => [
                            'label' => __('Star Color')
                        ]
                    ]
                );

                $container2->addChildren(
                    'star_active_color',
                    'color',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'star_active_color',
                        'defaultValue'    => '#ff5501',
                        'templateOptions' => [
                            'label' => __('Star Active Color')
                        ]
                    ]
                );

            $advanced->addChildren(
                'star_values',
                'textarea',
                [
                    'sortOrder'       => 40,
                    'key'             => 'star_values',
                    'defaultValue'    => '1==Bad
2==Could be better
3==So so
4==Good
5==Excellent!',
                    'templateOptions' => [
                        'label' => __('Values')
                    ]
                ]
            );

        return $advanced;
    }
}