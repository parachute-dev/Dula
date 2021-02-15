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

class ChoiceMatrix extends AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareColumnsTab();
    	$this->prepareRowsTab();
    	$this->prepareAdvancedTab();
    	return $this;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareColumnsTab()
    {
        $options = $this->addTab(
            'tab_columns',
            [
                'sortOrder'       => 30,
                'templateOptions' => [
                    'label' => __('Columns')
                ]
            ]
        );

        	$columns = $options->addChildren(
                'columns',
                'dynamicRows',
                [
					'key'       => 'columns',
					'className' => 'mgz-dynamicrows-table',
					'sortOrder' => 10
                ]
            );

            	$container1 = $columns->addContainerGroup(
	                'container1',
	                [
						'templateOptions' => [
	                        'sortOrder' => 10
	                    ]
	                ]
	            );

	            	$container1->addChildren(
			            'title',
			            'text',
			            [
			                'key'             => 'title',
			                'sortOrder'       => 10,
			                'templateOptions' => [
								'label' => __('Title')
			                ]
			            ]
			        );

                    $container4 = $container1->addContainer(
                        'container4',
                        [
                            'className' => 'mgz-dynamicrows-actions',
                            'sortOrder' => 20
                        ]
                    );

                        $container4->addChildren(
                            'delete',
                            'actionDelete',
                            [
                                'sortOrder' => 10
                            ]
                        );

                        $container4->addChildren(
                            'position',
                            'text',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'position',
                                'templateOptions' => [
                                    'element' => 'Magezon_Builder/js/form/element/dynamic-rows/position'
                                ]
                            ]
                        );

        return $options;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareRowsTab()
    {
        $options = $this->addTab(
            'tab_rows',
            [
                'sortOrder'       => 40,
                'templateOptions' => [
                    'label' => __('Rows')
                ]
            ]
        );

        	$rows = $options->addChildren(
                'rows',
                'dynamicRows',
                [
					'key'       => 'rows',
					'className' => 'mgz-dynamicrows-table',
					'sortOrder' => 10
                ]
            );

            	$container1 = $rows->addContainerGroup(
	                'container1',
	                [
						'templateOptions' => [
	                        'sortOrder' => 10
	                    ]
	                ]
	            );

	            	$container1->addChildren(
			            'title',
			            'text',
			            [
			                'key'             => 'title',
			                'sortOrder'       => 10,
			                'templateOptions' => [
								'label' => __('Title')
			                ]
			            ]
			        );

                    $container4 = $container1->addContainer(
                        'container4',
                        [
                            'className' => 'mgz-dynamicrows-actions',
                            'sortOrder' => 20
                        ]
                    );

                        $container4->addChildren(
                            'delete',
                            'actionDelete',
                            [
                                'sortOrder' => 10
                            ]
                        );

                        $container4->addChildren(
                            'position',
                            'text',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'position',
                                'templateOptions' => [
                                    'element' => 'Magezon_Builder/js/form/element/dynamic-rows/position'
                                ]
                            ]
                        );

        return $options;
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
	                'multiple',
	                'toggle',
	                [
						'sortOrder'       => 20,
						'key'             => 'multiple',
						'templateOptions' => [
	                        'label' => __('Multiple Select')
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

            $container3 = $advanced->addContainerGroup(
                'container3',
                [
                    'sortOrder' => 30
                ]
            );

                $container3->addChildren(
                    'odd_rows_color',
                    'color',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'odd_rows_color',
                        'templateOptions' => [
                            'label' => __('Odd Rows Color')
                        ]
                    ]
                );

                $container3->addChildren(
                    'odd_rows_background_color',
                    'color',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'odd_rows_background_color',
                        'templateOptions' => [
                            'label' => __('Odd Rows Background Color')
                        ]
                    ]
                );

            $container4 = $advanced->addContainerGroup(
                'container4',
                [
                    'sortOrder' => 40
                ]
            );

                $container4->addChildren(
                    'even_rows_color',
                    'color',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'even_rows_color',
                        'templateOptions' => [
                            'label' => __('Even Rows Color')
                        ]
                    ]
                );

                $container4->addChildren(
                    'even_rows_background_color',
                    'color',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'even_rows_background_color',
                        'defaultValue'    => '#f5f5f5',
                        'templateOptions' => [
                            'label' => __('Even Rows Background Color')
                        ]
                    ]
                );

            $container5 = $advanced->addContainerGroup(
                'container5',
                [
                    'sortOrder' => 50
                ]
            );

                $container5->addChildren(
                    'cell_border_width',
                    'number',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'cell_border_width',
                        'templateOptions' => [
                            'label' => __('Cell Boder Width')
                        ]
                    ]
                );

                $container5->addChildren(
                    'cell_border_style',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'cell_border_style',
                        'defaultValue'    => 'solid',
                        'templateOptions' => [
                            'label'   => __('Cell Border Style'),
                            'options' => $this->getBorderStyle()
                        ]
                    ]
                );

            $container6 = $advanced->addContainerGroup(
                'container6',
                [
                    'sortOrder' => 60
                ]
            );

                $container6->addChildren(
                    'cell_border_color',
                    'color',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'cell_border_color',
                        'defaultValue'    => '#333',
                        'templateOptions' => [
                            'label' => __('Cell Border Color')
                        ]
                    ]
                );

                $container6->addChildren(
                    'heading_cell_border_color',
                    'color',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'heading_cell_border_color',
                        'defaultValue'    => '#007dbd',
                        'templateOptions' => [
                            'label' => __('Heading Cell Border Color')
                        ]
                    ]
                );

        return $advanced;
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        return [
            'columns' => [
                [
                    'title'    => 'Column1',
                    'position' => 1
                ],
                [
                    'title'    => 'Column2',
                    'position' => 2
                ],
                [
                    'title'    => 'Column3',
                    'position' => 3
                ]
            ],
            'rows' => [
                [
                    'title'    => 'Row1',
                    'position' => 1
                ],
                [
                    'title'    => 'Row2',
                    'position' => 2
                ],
                [
                    'title'    => 'Row3',
                    'position' => 3
                ]
            ]
        ];
    }
}