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

class Email extends AbstractElement
{
	const FIELD_EMAIL_AUTORESPONDER = 'autoresponder';

    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareIconTab();
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
	                self::FIELD_HIDDEN,
	                'toggle',
	                [
						'sortOrder'       => 20,
						'key'             => self::FIELD_HIDDEN,
						'templateOptions' => [
	                        'label' => __('Hidden Field')
	                    ]
	                ]
	            );

            	$container1->addChildren(
	                self::FIELD_READONLY,
	                'toggle',
	                [
						'sortOrder'       => 30,
						'key'             => self::FIELD_READONLY,
						'templateOptions' => [
	                        'label' => __('Read-Only Field')
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
	                self::FIELD_AUTOTOMPLETE,
	                'toggle',
	                [
						'sortOrder'       => 10,
						'key'             => self::FIELD_AUTOTOMPLETE,
						'templateOptions' => [
	                        'label' => __('Browser Autocomplete')
	                    ]
	                ]
	            );

	            $container2->addChildren(
	                self::FIELD_AUTOTOFOCUS,
	                'toggle',
	                [
	                    'sortOrder'       => 20,
	                    'key'             => self::FIELD_AUTOTOFOCUS,
	                    'templateOptions' => [
							'label'        => __('Autofocus'),
							'tooltipClass' => 'tooltip-bottom tooltip-bottom-left',
							'tooltip'      => __('When present, it specifies that the element should automatically get focus when the page loads.')
	                    ]
	                ]
	            );

        	$advanced->addChildren(
                self::FIELD_EMAIL_AUTORESPONDER,
                'toggle',
                [
					'sortOrder'       => 30,
					'key'             => self::FIELD_EMAIL_AUTORESPONDER,
					'defaultValue'    => true,
					'templateOptions' => [
						'label' => __('Send Autoresponder'),
						'note'  => __('Autoresponder settings are available under <b>Settings > Email Notifications > Customer</b>')
                    ]
                ]
            );

	    	$advanced->addChildren(
	            self::FIELD_DEFAULT_VALUE,
	            'text',
	            [
					'sortOrder'       => 40,
					'key'             => self::FIELD_DEFAULT_VALUE,
					'defaultValue'    => '[customer.email]',
					'templateOptions' => [
						'label'       => __('Default Value'),
						'templateUrl' => 'BlueFormBuilder_Core/js/templates/modal/form/element/smart_variables.html',
						'element'     => 'BlueFormBuilder_Core/js/modal/element/smart-fields',
	                ]
	            ]
	        );

	    	$advanced->addChildren(
	            self::FIELD_PLACEHOLDER,
	            'text',
	            [
					'sortOrder'       => 50,
					'key'             => self::FIELD_PLACEHOLDER,
					'templateOptions' => [
	                    'label' => __('Placeholder')
	                ]
	            ]
	        );

        return $advanced;
    }
}