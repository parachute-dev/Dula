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

class Pages extends \Magezon\Builder\Data\Element\AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareTab();
    	$this->prepareButtonDesignTab();
    	return $this;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareGeneralTab()
    {
    	$general = parent::prepareGeneralTab();

    		$container1 = $general->addContainerGroup(
	            'container1',
	            [
					'sortOrder' => 10
	            ]
		    );

	    		$container1->addChildren(
	    			'indicator',
	    			'select',
	    			[
	    				'sortOrder'       => 10,
	    				'key'             => 'indicator',
	    				'defaultValue'    => 'progress',
	    				'templateOptions' => [
							'label'   => __('Progress Indicator'),
							'element' => 'Magezon_Builder/js/form/element/dependency',
							'options' => $this->getIndicatorOptions(),
							'groupsConfig' => [
								'tabs' => [
									'tab_item'
								]
							]
	    				]
	    			]
	    		);

	    		$container1->addChildren(
	    			'indicator_color',
	    			'color',
	    			[
	    				'sortOrder'       => 20,
	    				'key'             => 'indicator_color',
	    				'defaultValue'    => '#72b239',
	    				'templateOptions' => [
							'label' => __('Page Indicator Color')
	    				],
						'hideExpression' => 'model.indicator=="tabs"'
	    			]
	    		);

    		$container2 = $general->addContainerGroup(
	            'container2',
	            [
					'sortOrder'      => 20,
					'hideExpression' => 'model.indicator!="tabs"'
	            ]
		    );

	    		$container2->addChildren(
		            'enable_box_shadow',
		            'toggle',
		            [
						'sortOrder'       => 10,
						'key'             => 'enable_box_shadow',
						'defaultValue'    => true,
						'templateOptions' => [
							'label' => __('Enable Box Shadow')
		                ]
		            ]
		        );

	    		$container2->addChildren(
		            'heading_background_color',
		            'color',
		            [
						'sortOrder'       => 20,
						'key'             => 'heading_background_color',
						'templateOptions' => [
							'label' => __('Heading Background Color')
		                ]
		            ]
		        );

		    	$container2->addChildren(
		            'heading_border_color',
		            'color',
		            [
						'sortOrder'       => 30,
						'key'             => 'heading_border_color',
						'templateOptions' => [
							'label' => __('Heading Border Color')
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
		            'gap',
		            'text',
		            [
						'sortOrder'       => 10,
						'key'             => 'gap',
						'templateOptions' => [
							'label' => __('Gap')
		                ]
		            ]
		        );

		    	$container3->addChildren(
		            'active_tab',
		            'text',
		            [
						'sortOrder'       => 20,
						'key'             => 'active_tab',
						'defaultValue'    => 1,
						'templateOptions' => [
							'label' => __('Active Page'),
							'note'  => __('Enter active page number. Leave empty or enter non-existing number to close all tabs on page load.')
		                ]
		            ]
		        );

	    	$general->addChildren(
	            'hide_empty_tab',
	            'toggle',
	            [
					'sortOrder'       => 50,
					'key'             => 'hide_empty_tab',
					'templateOptions' => [
						'label' => __('Hide Empty Page')
	                ]
	            ]
	        );

    	return $general;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareTab()
    {
    	$tab = $this->addTab(
            'tab_item',
            [
                'sortOrder'       => 50,
                'templateOptions' => [
                    'label' => __('Tab Item')
                ]
            ]
        );

        	$tab->addChildren(
	            'note',
	            'html',
	            [
					'sortOrder' => 0,
					'content'   => 'HELLO'
	            ]
		    );


	        $container1 = $tab->addContainerGroup(
	            'container1',
	            [
					'sortOrder' => 10
	            ]
		    );

		    	$container1->addChildren(
		            'tab_align',
		            'select',
		            [
						'sortOrder'       => 10,
						'key'             => 'tab_align',
						'defaultValue'    => 'left',
						'templateOptions' => [
							'label'   => __('Alignment'),
							'options' => $this->getAlignOptions()
		                ]
		            ]
		        );

		    	$container1->addChildren(
		            'tab_position',
		            'select',
		            [
						'sortOrder'       => 20,
						'key'             => 'tab_position',
						'defaultValue'    => 'top',
						'templateOptions' => [
							'label'   => __('Position'),
							'options' => $this->getPositionOptions()
		                ]
		            ]
		        );

		    	$container1->addChildren(
		            'spacing',
		            'text',
		            [
						'sortOrder'       => 30,
						'key'             => 'spacing',
						'templateOptions' => [
							'label' => __('Spacing')
		                ]
		            ]
		        );

	        $border1 = $tab->addContainerGroup(
	            'border1',
	            [
					'sortOrder' => 20
	            ]
	        );

		    	$border1->addChildren(
		            'tab_border_width',
		            'text',
		            [
						'sortOrder'       => 10,
						'key'             => 'tab_border_width',
						'templateOptions' => [
							'label' => __('Border Width')
		                ]
		            ]
		        );

		    	$border1->addChildren(
		            'tab_border_radius',
		            'text',
		            [
						'sortOrder'       => 20,
						'key'             => 'tab_border_radius',
						'templateOptions' => [
							'label' => __('Border Radius')
		                ]
		            ]
		        );

                $border1->addChildren(
                    'tab_border_style',
                    'select',
                    [
						'key'             => 'tab_border_style',
						'sortOrder'       => 30,
						'templateOptions' => [
							'label'       => __('Border Style'),
							'options'     => $this->getBorderStyle(),
							'placeholder' => __('Theme defaults')
                        ]
                    ]
                );

        	$colors = $tab->addTab(
	            'colors',
	            [
	                'sortOrder'       => 30,
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
				            'tab_color',
				            'color',
				            [
								'sortOrder'       => 10,
								'key'             => 'tab_color',
								'templateOptions' => [
									'label' => __('Text Color')
				                ]
				            ]
				        );

				    	$color1->addChildren(
				            'tab_background_color',
				            'color',
				            [
								'sortOrder'       => 20,
								'key'             => 'tab_background_color',
								'templateOptions' => [
									'label' => __('Background Color')
				                ]
				            ]
				        );

				    	$color1->addChildren(
				            'tab_border_color',
				            'color',
				            [
								'sortOrder'       => 30,
								'key'             => 'tab_border_color',
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
				            'tab_hover_color',
				            'color',
				            [
								'sortOrder'       => 10,
								'key'             => 'tab_hover_color',
								'templateOptions' => [
									'label' => __('Text Color')
				                ]
				            ]
				        );

				    	$color2->addChildren(
				            'tab_hover_background_color',
				            'color',
				            [
								'sortOrder'       => 20,
								'key'             => 'tab_hover_background_color',
								'templateOptions' => [
									'label' => __('Background Color')
				                ]
				            ]
				        );

				    	$color2->addChildren(
				            'tab_hover_border_color',
				            'color',
				            [
								'sortOrder'       => 30,
								'key'             => 'tab_hover_border_color',
								'templateOptions' => [
									'label' => __('Border Color')
				                ]
				            ]
				        );

	        	$active = $colors->addContainerGroup(
		            'active',
		            [
						'sortOrder'       => 30,
						'templateOptions' => [
							'label' => __('Active')
		                ]
		            ]
		        );

			        $color3 = $active->addContainerGroup(
			            'color3',
			            [
							'sortOrder' => 10
			            ]
			        );

				    	$color3->addChildren(
				            'tab_active_color',
				            'color',
				            [
								'sortOrder'       => 10,
								'key'             => 'tab_active_color',
								'templateOptions' => [
									'label' => __('Text Color')
				                ]
				            ]
				        );

				    	$color3->addChildren(
				            'tab_active_background_color',
				            'color',
				            [
								'sortOrder'       => 20,
								'key'             => 'tab_active_background_color',
								'templateOptions' => [
									'label' => __('Background Color')
				                ]
				            ]
				        );

				    	$color3->addChildren(
				            'tab_active_border_color',
				            'color',
				            [
								'sortOrder'       => 30,
								'key'             => 'tab_active_border_color',
								'templateOptions' => [
									'label' => __('Border Color')
				                ]
				            ]
				        );

        return $tab;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareButtonDesignTab()
    {
    	$button = $this->addTab(
            'tab_button_design',
            [
                'sortOrder'       => 50,
                'templateOptions' => [
                    'label' => __('Button')
                ]
            ]
        );

    		$container1 = $button->addContainerGroup(
	            'container1',
	            [
					'sortOrder' => 10
	            ]
		    );

		    	$container1->addChildren(
		            'next_label',
		            'text',
		            [
						'sortOrder'       => 10,
						'key'             => 'next_label',
						'defaultValue'    => 'Next',
						'templateOptions' => [
							'label' => __('Next Label')
		                ]
		            ]
		        );

		    	$container1->addChildren(
		            'prev_label',
		            'text',
		            [
						'sortOrder'       => 20,
						'key'             => 'prev_label',
						'defaultValue'    => 'Previous',
						'templateOptions' => [
							'label' => __('Previous Label')
		                ]
		            ]
		        );

	    		$container1->addChildren(
	    			'nav_align',
	    			'select',
	    			[
	    				'sortOrder'       => 30,
	    				'key'             => 'nav_align',
	    				'defaultValue'    => 'left',
	    				'templateOptions' => [
	    					'label'   => __('Alignment'),
	    					'options' => $this->getNavAlignOptions()
	    				]
	    			]
	    		);

        	$colors = $button->addTab(
	            'next_colors',
	            [
	                'sortOrder'       => 20,
	                'templateOptions' => [
	                    'label' => __('Next Button Colors')
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
				            'tab_color',
				            'color',
				            [
								'sortOrder'       => 10,
								'key'             => 'next_button_color',
								'templateOptions' => [
									'label' => __('Text Color')
				                ]
				            ]
				        );

				    	$color1->addChildren(
				            'tab_background_color',
				            'color',
				            [
								'sortOrder'       => 20,
								'key'             => 'next_button_background_color',
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
				            'button_hover_color',
				            'color',
				            [
								'sortOrder'       => 10,
								'key'             => 'next_button_hover_color',
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
								'key'             => 'next_button_hover_background_color',
								'templateOptions' => [
									'label' => __('Background Color')
				                ]
				            ]
				        );

        	$colors = $button->addTab(
	            'prev_colors',
	            [
	                'sortOrder'       => 30,
	                'templateOptions' => [
	                    'label' => __('Previous Button Colors')
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
				            'tab_color',
				            'color',
				            [
								'sortOrder'       => 10,
								'key'             => 'prev_button_color',
								'templateOptions' => [
									'label' => __('Text Color')
				                ]
				            ]
				        );

				    	$color1->addChildren(
				            'tab_background_color',
				            'color',
				            [
								'sortOrder'       => 20,
								'key'             => 'prev_button_background_color',
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
				            'button_hover_color',
				            'color',
				            [
								'sortOrder'       => 10,
								'key'             => 'prev_button_hover_color',
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
								'key'             => 'prev_button_hover_background_color',
								'templateOptions' => [
									'label' => __('Background Color')
				                ]
				            ]
				        );

        return $button;
    }

    /**
     * @return array
     */
    protected function getIndicatorOptions()
    {
        return [
            [
                'label' => __('Progress Bar'),
                'value' => 'progress'
            ],
            [
                'label' => __('Circles'),
                'value' => 'circles'
            ],
            [
                'label' => __('Connector'),
                'value' => 'connector'
            ],
            [
                'label' => __('Tabs'),
                'value' => 'tabs'
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getNavAlignOptions()
    {
        return [
            [
                'label' => __('Left'),
                'value' => 'left'
            ],
            [
                'label' => __('Right'),
                'value' => 'right'
            ],
            [
                'label' => __('Center'),
                'value' => 'center'
            ],
            [
                'label' => __('Split'),
                'value' => 'split'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getPositionOptions()
    {
        return [
            [
                'label' => __('Top'),
                'value' => 'top'
            ],
            [
                'label' => __('Bottom'),
                'value' => 'bottom'
            ]
        ];
    }
}