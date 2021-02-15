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

class Toggle extends AbstractElement
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
	                'toggle_color',
	                'color',
	                [
                        'sortOrder'       => 30,
                        'key'             => 'toggle_color',
                        'defaultValue'    => '#007dbd',
                        'templateOptions' => [
	                        'label' => __('Color')
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
                    'toggle_text_on',
                    'text',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'toggle_text_on',
                        'defaultValue'    => 'Yes',
                        'templateOptions' => [
                            'label' => __('Checked State Label')
                        ]
                    ]
                );

                $container2->addChildren(
                    'toggle_text_off',
                    'text',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'toggle_text_off',
                        'defaultValue'    => 'No',
                        'templateOptions' => [
                            'label' => __('Unchecked State Label')
                        ]
                    ]
                );

        return $advanced;
    }
}