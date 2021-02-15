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

class FieldSet extends \Magezon\Builder\Data\Element\AbstractElement
{
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
					'defaultValue'    => 'Fieldset',
					'templateOptions' => [
						'label' => __('Title')
	                ]
	            ]
	        );

	        $container1 = $general->addContainerGroup(
	            'container1',
	            [
					'sortOrder' => 20
	            ]
		    );

		    	$container1->addChildren(
		            'title_align',
		            'select',
		            [
						'sortOrder'       => 10,
						'key'             => 'title_align',
						'defaultValue'    => 'center',
						'templateOptions' => [
							'label'   => __('Title Alignment'),
							'options' => $this->getAlignOptions()
		                ]
		            ]
		        );

		    	$container1->addChildren(
		            'title_tag',
		            'select',
		            [
						'sortOrder'       => 20,
						'key'             => 'title_tag',
						'defaultValue'    => 'h3',
						'templateOptions' => [
							'label'   => __('Title Tag'),
							'options' => $this->getHeadingType()
		                ]
		            ]
		        );

        	$container2 = $general->addContainerGroup(
	            'container2',
	            [
					'sortOrder' => 30
	            ]
	        );

		        $container2->addChildren(
		            'heading_color',
		            'color',
		            [
						'sortOrder'       => 10,
						'key'             => 'heading_color',
						'defaultValue'    => '#FFF',
						'templateOptions' => [
							'label' => __('Heading Color')
		                ]
		            ]
		        );

		    	$container2->addChildren(
		            'heading_background_color',
		            'color',
		            [
						'sortOrder'       => 20,
						'key'             => 'heading_background_color',
						'defaultValue'    => '#007dbd',
						'templateOptions' => [
							'label' => __('Heading Background Color')
		                ]
		            ]
		        );

        	$container3 = $general->addContainerGroup(
	            'container3',
	            [
					'sortOrder' => 30
	            ]
	        );

		        $container3->addChildren(
		            'fieldset_padding',
		            'text',
		            [
						'sortOrder'       => 10,
						'key'             => 'fieldset_padding',
						'defaultValue'    => '15px',
						'templateOptions' => [
							'label' => __('Padding')
		                ]
		            ]
		        );

    	return $general;
    }

    public function getDefaultValues()
    {
    	return [
			'border_top_width'    => '1px',
			'border_left_width'   => '1px',
			'border_right_width'  => '1px',
			'border_bottom_width' => '1px',
			'border_style'        => 'solid',
			'border_color'        => '#007dbd'
    	];
    }
}