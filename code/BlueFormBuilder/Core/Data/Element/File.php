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

class File extends AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareIconTab();
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

            $validation->addChildren(
                'allowed_exttensions',
                'text',
                [
                    'sortOrder'       => 10,
                    'key'             => 'allowed_exttensions',
                    'defaultValue'    => 'jpg, jpef, gif, png, pdf',
                    'templateOptions' => [
                        'label' => __('Allowed Extensions'),
                        'note'  => __('Enter the file extensions users are allowed to upload, separated by a comma. Leave blank to allow all file-types.')
                    ]
                ]
            );

            $container1 = $validation->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 20
                ]
            );

                $container1->addChildren(
                    'min_files',
                    'number',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'min_files',
                        'templateOptions' => [
                            'label' => __('Min Files')
                        ]
                    ]
                );

                $container1->addChildren(
                    'max_files',
                    'number',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'max_files',
                        'templateOptions' => [
                            'label' => __('Max Files')
                        ]
                    ]
                );

            $container2 = $validation->addContainerGroup(
                'container2',
                [
                    'sortOrder' => 30
                ]
            );

                $container2->addChildren(
                    'min_file_size',
                    'number',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'min_file_size',
                        'templateOptions' => [
                            'label' => __('Min File Size(KB)')
                        ]
                    ]
                );

                $container2->addChildren(
                    'max_file_size',
                    'number',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'max_file_size',
                        'defaultValue'    => 1024,
                        'templateOptions' => [
                            'label' => __('Max File Size(KB)')
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
	                        'label' => __('Select multiple files at a time')
	                    ]
	                ]
	            );

            	$container1->addChildren(
	                'dragdrop',
	                'toggle',
	                [
                        'sortOrder'       => 30,
                        'key'             => 'dragdrop',
                        'templateOptions' => [
	                        'label' => __('Drag and Drop Interface')
	                    ]
	                ]
	            );

	    	$advanced->addChildren(
	            'dragdrop_description',
	            'text',
	            [
                    'sortOrder'       => 20,
                    'key'             => 'dragdrop_description',
                    'defaultValue'    => 'Drag and drop files or click to select',
                    'templateOptions' => [
	                    'label' => __('Drag and Drop Description')
	                ],
                    'hideExpression' => '!model.dragdrop'
	            ]
	        );

            $advanced->addChildren(
                'upload_btn_text',
                'text',
                [
                    'sortOrder'       => 20,
                    'key'             => 'upload_btn_text',
                    'defaultValue'    => 'Choose File',
                    'templateOptions' => [
                        'label' => __('Upload Button text')
                    ],
                    'hideExpression' => 'model.dragdrop'
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
            'icon'      => 'fas mgz-fa-cloud-upload-alt',
            'show_icon' => true
        ];
    }
}