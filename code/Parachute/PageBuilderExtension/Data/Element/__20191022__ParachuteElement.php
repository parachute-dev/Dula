<?php
namespace Parachute\PageBuilderExtension\Data\Element;

/**
 * The basis for all Parachute blocks.
 */

class ParachuteElement extends \Magezon\Builder\Data\Element\AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
        parent::prepareForm();
        $this->prepareBlockTab();
        $this->prepareContentTab();
        $this->prepareLayoutTab();

        // EXTENDING TABS
        // NOTE: If you're extending a tab of the parent class you don't need to call it
        // separately here - so long as it's called already somehow by the parent class
        // Having the method here that calls parent::prepareGeneralTab() is enough!
        // $this->prepareGeneralTab();

        return $this;
    }

    // EXTENDING TABS
    // NOTE: You don't need to call this in prepareForm() if it is already called by the parent
    // prepareGeneralTab() is a good example of extending a tab that already exists
    // Obviously, be wary of the field names etc.
    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareGeneralTab()
    {
    	$general = parent::prepareGeneralTab();

        $general->addChildren(
            'general_title',
            'text',
            [
                'sortOrder'       => 10,
                'key'             => 'general_title',
                'defaultValue'    => 'Default Title',
                'templateOptions' => [
                    'label' => __('Title')
                ]
            ]
        );

        return $general;
    }

    // OVERRIDING TABS
    // NOTE: If you want to stop a tab being instantiated then just don't call
    // parent::prepareContentTab() as you normally would in that preparation method
    // Not doing so either "removes" that tab or creates it entirely new if you wish to do so
    // public function prepareContentTab()
    // {
    //     // Do nothing
    // }

    public function prepareBlockTab()
    {
		// Items tab
        $tab = $this->addTab(
            'tab_block',
            [
                'sortOrder'       => 0,
                'templateOptions' => [
                    'label' => __('Block')
                ]
            ]
        );

            // Block container
            $block_container = $tab->addContainer(
                'block_container',
                [
                    'sortOrder'       => 10,
                    'templateOptions' => [
                        'label'       => __('Block'),
                        'collapsible' => false
                    ]
                ]
            );

                $block_container->addChildren(
                    'block_active',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'block_active',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Active?'),
                        ]
                    ]
                );

                $block_container->addChildren(
                    'block_header_show',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'block_header_show',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show Block Header?'),
                        ]
                    ]
                );

                $block_container->addChildren(
                    'block_footer_show',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'block_footer_show',
                        'defaultValue'    => false,
                        'templateOptions' => [
                            'label' => __('Show Block Footer?'),
                        ]
                    ]
                );

                $block_container->addChildren(
                    'block_title',
                    'text',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'block_title',
                        'defaultValue'    => '',
                        'templateOptions' => [
                            'label' => __('Title'),
                            'rows'  => 2
                        ]
                    ]
                );

                $block_container->addChildren(
                    'block_subtitle',
                    'text',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'block_subtitle',
                        'defaultValue'    => '',
                        'templateOptions' => [
                            'label' => __('Subtitle'),
                            'rows'  => 2
                        ]
                    ]
                );

                $block_container->addChildren(
                    'block_content',
                    'editor',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'block_content',
                        'defaultValue'    => '',
                        'templateOptions' => [
                            'label' => __('Content'),
                            'rows'  => 2
                        ]
                    ]
                );

                $block_container->addChildren(
                    'block_subcontent',
                    'editor',
                    [
                        'sortOrder'       => 40,
                        'key'             => 'block_subcontent',
                        'defaultValue'    => '',
                        'templateOptions' => [
                            'label' => __('Sub-Content'),
                            'rows'  => 2
                        ]
                    ]
                );

                $block_container->addChildren(
                    'block_image',
                    'image',
                    [
                        'sortOrder'       => 50,
                        'key'             => 'block_image',
                        'defaultValue'    => '',
                        'templateOptions' => [
                            'label' => __('Image'),
                            'rows'  => 2
                        ]
                    ]
                );

                // List of images
                $block_images_container_1 = $block_container->addContainerGroup(
                    'block_image_container_1',
                    [
                        'sortOrder' => 60,
                        'templateOptions' => [
                            'label' => __('Image list'),
                            'collapsible' => true
                        ]
                    ]
                );

                    $block_image_list = $block_images_container_1->addChildren(
                        'image_list',
                        'dynamicRows',
                        [
                            'key'             => 'image_list',
                            'sortOrder'       => 60,
                            'templateOptions' => [
                                'displayIndex' => true,
                                'collapsible' => true,
                                'label' => __('Image list') 
                            ]
                        ]
                    );
                        $block_image_list->addChildren(
                            'image_item',
                            'image',
                            [
                                'sortOrder'       => 10,
                                'key'             => 'image',
                                'templateOptions' => [
                                    'label'   => __('Image')
                                ]
                            ]
                        );

                        $block_image_list->addChildren(
                            'image_title',
                            'text',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'image_title',
                                'templateOptions' => [
                                    'label'   => __('Title')
                                ]
                            ]
                        );

                        $block_image_list->addChildren(
                            'image_subtitle',
                            'text',
                            [
                                'sortOrder'       => 30,
                                'key'             => 'image_subtitle',
                                'templateOptions' => [
                                    'label'   => __('Subtitle')
                                ]
                            ]
                        );

                        $block_image_list->addChildren(
                            'image_content',
                            'editor',
                            [
                                'sortOrder'       => 40,
                                'key'             => 'image_content',
                                'templateOptions' => [
                                    'label'   => __('Content')
                                ]
                            ]
                        );

                        $block_image_list->addChildren(
                            'image_subcontent',
                            'editor',
                            [
                                'sortOrder'       => 50,
                                'key'             => 'image_subcontent',
                                'templateOptions' => [
                                    'label'   => __('Sub-Content')
                                ]
                            ]
                        );

                        $block_image_list->addChildren(
                            'image_cta_type',
                            'select',
                            [
                                'sortOrder'       => 60,
                                'key'             => 'image_cta_type',
                                'templateOptions' => [
                                    'label'   => __('CTA Type'),
                                    'options' => $this->getSlideLinkType()
                                ]
                            ]
                        );

                        $block_image_list->addChildren(
                            'image_cta_link',
                            'text',
                            [
                                'sortOrder'       => 70,
                                'key'             => 'image_cta_link',
                                'templateOptions' => [
                                    'label'   => __('CTA Link')
                                ]
                            ]
                        );

                        $block_image_list->addChildren(
                            'image_cta_text',
                            'text',
                            [
                                'sortOrder'       => 80,
                                'key'             => 'image_cta_text',
                                'templateOptions' => [
                                    'label'   => __('CTA Text')
                                ]
                            ]
                        );
                // End List of images

                // Call to action
                $link_container = $tab->addContainer(
                    'link_container',
                    [
                        'sortOrder'       => 50,
                        'templateOptions' => [
                            'label'       => __('Call to Action (CTA)'),
                            'collapsible' => true
                        ]
                    ]
                );

                    $link_inner_container_1 = $link_container->addContainerGroup(
                        'link_inner_container_1',
                        [
                            'sortOrder' => 10,
                            'templateOptions' => [
                                'label' => __('Primary Content CTA')
                            ]
                        ]
                    );

                        $link_inner_container_1->addChildren(
                            'block_cta_type',
                            'select',
                            [
                                'sortOrder'       => 10,
                                'key'             => 'block_cta_type',
                                'defaultValue'    => 'full',
                                'templateOptions' => [
                                    'label'   => __('CTA Type'),
                                    'options' => $this->getSlideLinkType()
                                ]
                            ]
                        );

                        $link_inner_container_1->addChildren(
                            'block_cta_target',
                            'toggle',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'block_cta_target',
                                'templateOptions' => [
                                    'label'   => __('Open Slide Link In New Window'),
                                ]
                            ]
                        );

                        $link_inner_container_1->addChildren(
                            'block_cta_link',
                            'text',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'block_cta_link',
                                'templateOptions' => [
                                    'label' => __('Call to Action Link')
                                ]
                            ]
                        );

                        $link_inner_container_1->addChildren(
                            'block_cta_link_text',
                            'text',
                            [
                                'sortOrder'       => 30,
                                'key'             => 'block_cta_link_text',
                                'templateOptions' => [
                                    'label' => __('CTA Text')
                                ]
                            ]
                        );

                    $link_inner_container_2 = $link_container->addContainerGroup(
                        'link_inner_container_2',
                        [
                            'sortOrder' => 10,
                            'templateOptions' => [
                                'label' => __('Sub-Content CTA')
                            ]
                        ]
                    );

                        $link_inner_container_2->addChildren(
                            'block_subcontent_cta_type',
                            'select',
                            [
                                'sortOrder'       => 10,
                                'key'             => 'block_subcontent_cta_type',
                                'defaultValue'    => 'full',
                                'templateOptions' => [
                                    'label'   => __('CTA Type'),
                                    'options' => $this->getSlideLinkType()
                                ]
                            ]
                        );

                        $link_inner_container_2->addChildren(
                            'block_subcontent_cta_target',
                            'toggle',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'block_subcontent_cta_target',
                                'templateOptions' => [
                                    'label'   => __('Open Slide Link In New Window'),
                                ]
                            ]
                        );

                        $link_inner_container_2->addChildren(
                            'block_subcontent_cta_link',
                            'text',
                            [
                                'sortOrder'       => 20,
                                'key'             => 'block_subcontent_cta_link',
                                'templateOptions' => [
                                    'label' => __('Call to Action Link')
                                ]
                            ]
                        );

                        $link_inner_container_2->addChildren(
                            'subcontent_cta_cta_text',
                            'text',
                            [
                                'sortOrder'       => 30,
                                'key'             => 'subcontent_cta_link_text',
                                'templateOptions' => [
                                    'label' => __('CTA Text')
                                ]
                            ]
                        );
                // End Call to Action

                // $block_container->addChildren(
                //     'block_link_type',
                //     'select',
                //     [
                //         'sortOrder'       => 60,
                //         'key'             => 'block_cta_type',
                //         'defaultValue'    => 'full',
                //         'templateOptions' => [
                //             'label'   => __('CTA Type'),
                //             'options' => $this->getSlideLinkType()
                //         ]
                //     ]
                // );

                // $block_container->addChildren(
                //     'block_slide_target',
                //     'toggle',
                //     [
                //         'sortOrder'       => 70,
                //         'key'             => 'block_cta_target',
                //         'templateOptions' => [
                //             'label'   => __('Open Slide Link In New Window'),
                //         ]
                //     ]
                // );

                // $block_container->addChildren(
                //     'block_slide_link',
                //     'text',
                //     [
                //         'sortOrder'       => 80,
                //         'key'             => 'block_cta_link',
                //         'templateOptions' => [
                //             'label' => __('Call to Action Link')
                //         ]
                //     ]
                // );

                // $block_container->addChildren(
                //     'block_slide_link_text',
                //     'text',
                //     [
                //         'sortOrder'       => 90,
                //         'key'             => 'block_cta_link_text',
                //         'templateOptions' => [
                //             'label' => __('CTA Text')
                //         ]
                //     ]
                // );

            // Position/Alignment
            $position_container = $tab->addContainerGroup(
                'position_container',
                [
                    'sortOrder' => 70,
                    'templateOptions' => [
                        'sortOrder' => 70,
                        'label' => __('Position/Alignment'),
                        'collapsible' => true
                    ]
                ]
            );

                $position_container->addChildren(
                    'content_justify',
                    'select',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'content_justify',
                        'defaultValue'    => 'start',
                        'templateOptions' => [
                            'label'   => __('Content Justify'),
                            'options' => $this->getJustifyOptions()
                        ]
                    ]
                );

                $position_container->addChildren(
                    'content_align',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'content_align',
                        'defaultValue'    => 'start',
                        'templateOptions' => [
                            'label'   => __('Content Alignment'),
                            'options' => $this->getAlignOptions()
                        ]
                    ]
                );

                $position_container->addChildren(
                    'alignment',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'alignment',
                        'defaultValue'    => 'left',
                        'templateOptions' => [
                            'label'   => __('Alignment'),
                            'options' => $this->getContentAlignOptions()
                        ]
                    ]
                );

                $position_container->addChildren(
                    'text_align',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'text_align',
                        'defaultValue'    => 'left',
                        'templateOptions' => [
                            'label'   => __('Text Alignment'),
                            'options' => $this->getTextAlignOptions()
                        ]
                    ]
                );

                $position_container->addChildren(
                    'content_size',
                    'select',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'content_size',
                        'defaultValue'    => 'medium',
                        'templateOptions' => [
                            'label'   => __('Content Size'),
                            'options' => $this->getSizeOptions()
                        ]
                    ]
                );
            // End Position/Alignment

            // Layout
            $block_layout_container = $tab->addContainerGroup(
                'block_layout_container',
                [
                    'sortOrder' => 70,
                    'templateOptions' => [
                        'sortOrder' => 70,
                        'label' => __('Container'),
                        'collapsible' => true
                    ]
                ]
            );

                $block_layout_container->addChildren(
                    'container_type',
                    'select',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'container_type',
                        'defaultValue'    => 'fluid',
                        'templateOptions' => [
                            'label' => __('Container type (Outer)'),
                            'options' => $this->getContainerTypeOptions()
                        ]
                    ]
                );

                $block_layout_container->addChildren(
                    'container_type_inner',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'container_type_inner',
                        'defaultValue'    => 'fixed',
                        'templateOptions' => [
                            'label' => __('Container type (Inner)'),
                            'options' => $this->getContainerTypeOptions()
                        ]
                    ]
                );

                $block_layout_container->addChildren(
                    'no_gutter',
                    'toggle',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'no_gutter',
                        'defaultValue'    => false,
                        'templateOptions' => [
                            'label' => __('No gutter?')
                        ]
                    ]
                );

                $block_layout_container->addChildren(
                    'no_gutter_inner',
                    'toggle',
                    [
                        'sortOrder'       => 40,
                        'key'             => 'no_gutter_inner',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('No gutter (Inner)?')
                        ]
                    ]
                );
            // End layout
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareContentTab()
    {
		// Items tab
        $tab = $this->addTab(
            'tab_content',
            [
                'sortOrder'       => 0,
                'templateOptions' => [
                    'label' => __('Content')
                ]
            ]
        );

            // Active
            $active_container = $tab->addContainer(
                'heading_container',
                [
                    'sortOrder'       => 10,
                    'templateOptions' => [

                    ]
                ]
            );

                $active_container->addChildren(
                    'active',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'active',
                        'defaultValue'    => '',
                        'templateOptions' => [
                            'label' => __('Active?'),
                        ]
                    ]
                );

            // Title
            $title_container = $tab->addContainer(
                'title_container',
                [
                    'sortOrder'       => 10,
                    'templateOptions' => [
                        'label'       => __('Title'),
                        'collapsible' => true
                    ]
                ]
            );
                $title_container->addChildren(
                    'title',
                    'text',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'title',
                        'defaultValue'    => 'This is a title',
                        'templateOptions' => [
                            'label' => __('Title'),
                            'rows'  => 2
                        ]
                    ]
                );

                $title_inner_container = $title_container->addContainerGroup(
                    'container1',
                    [
                        'sortOrder'      => 20,
                        'hideExpression' => '!model.title'
                    ]
                );
            // End title
                
            // Subtitle
            $subtitle_container = $tab->addContainer(
                'subtitle_container',
                [
                    'sortOrder'       => 20,
                    'templateOptions' => [
                        'label'       => __('Subtitle'),
                        'collapsible' => true
                    ]
                ]
            );

                $subtitle_container->addChildren(
                    'subtitle',
                    'text',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'subtitle',
                        'templateOptions' => [
                            'label' => __('Subtitle'),
                            'rows'  => 2
                        ]
                    ]
                );
            // End subtitle

            // Sub-Content
            $subcontent_container = $tab->addContainer(
                'subcontent_container',
                [
                    'sortOrder'       => 30,
                    'templateOptions' => [
                        'label'       => __('Sub-Content'),
                        'collapsible' => true
                    ]
                ]
            );

                $subcontent_container->addChildren(
                    'sub_content',
                    'editor',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'subcontent',
                        'templateOptions' => [
                            'label' => __('Sub-Content'),
                            'rows'  => 2
                        ]
                    ]
                );
            // End Sub-Content

            // Content
            $content_container = $tab->addContainer(
                'content_container',
                [
                    'sortOrder'       => 40,
                    'templateOptions' => [
                        'label'       => __('Content'),
                        'collapsible' => true
                    ]
                ]
            );

                $content_container->addChildren(
                    'content',
                    'editor',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'content',
                        'templateOptions' => [
                            'label' => __('Content'),
                            'rows'  => 2
                        ]
                    ]
                );
            // End Content
                
            // // Call to action
            // $link_container = $tab->addContainer(
            //     'link_container',
            //     [
            //         'sortOrder'       => 50,
            //         'templateOptions' => [
            //             'label'       => __('Call to Action (CTA)'),
            //             'collapsible' => true
            //         ]
            //     ]
            // );

            //     $link_inner_container_1 = $link_container->addContainerGroup(
            //         'link_inner_container_1',
            //         [
            //             'sortOrder' => 10,
            //             'templateOptions' => [
            //                 'label' => __('Primary Content CTA')
            //             ]
            //         ]
            //     );

            //         $link_inner_container_1->addChildren(
            //             'link_type',
            //             'select',
            //             [
            //                 'sortOrder'       => 10,
            //                 'key'             => 'cta_type',
            //                 'defaultValue'    => 'full',
            //                 'templateOptions' => [
            //                     'label'   => __('CTA Type'),
            //                     'options' => $this->getSlideLinkType()
            //                 ]
            //             ]
            //         );

            //         $link_inner_container_1->addChildren(
            //             'slide_target',
            //             'toggle',
            //             [
            //                 'sortOrder'       => 20,
            //                 'key'             => 'cta_target',
            //                 'templateOptions' => [
            //                     'label'   => __('Open Slide Link In New Window'),
            //                 ]
            //             ]
            //         );

            //         $link_inner_container_1->addChildren(
            //             'slide_link',
            //             'text',
            //             [
            //                 'sortOrder'       => 20,
            //                 'key'             => 'cta_link',
            //                 'templateOptions' => [
            //                     'label' => __('Call to Action Link')
            //                 ]
            //             ]
            //         );

            //         $link_inner_container_1->addChildren(
            //             'slide_link_text',
            //             'text',
            //             [
            //                 'sortOrder'       => 30,
            //                 'key'             => 'cta_link_text',
            //                 'templateOptions' => [
            //                     'label' => __('CTA Text')
            //                 ]
            //             ]
            //         );

            //     $link_inner_container_2 = $link_container->addContainerGroup(
            //         'link_inner_container_2',
            //         [
            //             'sortOrder' => 10,
            //             'templateOptions' => [
            //                 'label' => __('Sub-Content CTA')
            //             ]
            //         ]
            //     );

            //         $link_inner_container_2->addChildren(
            //             'subcontent_link_type',
            //             'select',
            //             [
            //                 'sortOrder'       => 10,
            //                 'key'             => 'subcontent_cta_type',
            //                 'defaultValue'    => 'full',
            //                 'templateOptions' => [
            //                     'label'   => __('CTA Type'),
            //                     'options' => $this->getSlideLinkType()
            //                 ]
            //             ]
            //         );

            //         $link_inner_container_2->addChildren(
            //             'subcontent_slide_target',
            //             'toggle',
            //             [
            //                 'sortOrder'       => 20,
            //                 'key'             => 'subcontent_cta_target',
            //                 'templateOptions' => [
            //                     'label'   => __('Open Slide Link In New Window'),
            //                 ]
            //             ]
            //         );

            //         $link_inner_container_2->addChildren(
            //             'subcontent_slide_link',
            //             'text',
            //             [
            //                 'sortOrder'       => 20,
            //                 'key'             => 'subcontent_cta_link',
            //                 'templateOptions' => [
            //                     'label' => __('Call to Action Link')
            //                 ]
            //             ]
            //         );

            //         $link_inner_container_2->addChildren(
            //             'subcontent_cta_link_text',
            //             'text',
            //             [
            //                 'sortOrder'       => 30,
            //                 'key'             => 'subcontent_cta_link_text',
            //                 'templateOptions' => [
            //                     'label' => __('CTA Text')
            //                 ]
            //             ]
            //         );
            // // End Call to Action
                
            // Background/image
            $bg_image_container = $tab->addContainerGroup(
                'bg_image_container',
                [
                    'sortOrder' => '60',
                    'templateOptions' => [
                        'sortOrder' => 60,
                        'label' => __('Images'),
                        'collapsible' => true
                    ]
                ]
            );

                $bg_image_inner_container_1 = $bg_image_container->addContainerGroup(
                    'bg_image_inner_container_1',
                    [
                        'sortOrder' => 10,
                        'templateOptions' => [
                            'label' => __('Primary Image')
                        ]
                    ]
                );

                // Primary image
                $bg_image_inner_container_1->addChildren(
                    'background_type',
                    'select',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'background_type',
                        'defaultValue'    => 'image',
                        'templateOptions' => [
                            'label'   => __('Background Type'),
                            'options' => $this->getBackgroundType()
                        ]
                    ]
                );

                    $bg_image_inner_container_1->addChildren(
                        'image',
                        'image',
                        [
                            'sortOrder'       => 20,
                            'key'             => 'image',
                            'templateOptions' => [
                                'label' => __('Image')
                            ],
                            'hideExpression' => 'model.background_type!="image"'
                        ]
                    );


                    $bg_image_inner_container_1->addChildren(
                        'video_url',
                        'text',
                        [
                            'sortOrder'       => 20,
                            'key'             => 'video_url',
                            'templateOptions' => [
                                'label' => __('Video URL'),
                            ],
                            'hideExpression' => 'model.background_type!="video_url"'
                        ]
                    );

                    $bg_image_inner_container_1->addChildren(
                        'youtube_id',
                        'text',
                        [
                            'sortOrder'       => 20,
                            'key'             => 'youtube_id',
                            'templateOptions' => [
                                'label' => __('Youtube Video ID'),
                                'note'  => 'For example the Video ID for https://www.youtube.com/watch?v=<strong>HPan7HtIYOw</strong> is <strong>HPan7HtIYOw</strong>'
                            ],
                            'hideExpression' => 'model.background_type!="youtube"'
                        ]
                    );

                    $bg_image_inner_container_1->addChildren(
                        'vimeo_id',
                        'text',
                        [
                            'sortOrder'       => 30,
                            'key'             => 'vimeo_id',
                            'templateOptions' => [
                                'label' => __('Vimeo Video ID'),
                                'note'  => 'For example the Video ID for https://player.vimeo.com/video/<strong>156767727</strong> is <strong>156767727</strong>'
                            ],
                            'hideExpression' => 'model.background_type!="vimeo"'
                        ]
                    );
                // End Primary Image

                // Secondary Image
                $bg_image_inner_container_2 = $bg_image_container->addContainerGroup(
                    'bg_image_inner_container_2',
                    [
                        'sortOrder' => 10,
                        'templateOptions' => [
                            'label' => __('Secondary Image')
                        ]
                    ]
                );

                    $bg_image_inner_container_2->addChildren(
                        'secondary_background_type',
                        'select',
                        [
                            'sortOrder'       => 30,
                            'key'             => 'secondary_background_type',
                            'defaultValue'    => 'image',
                            'templateOptions' => [
                                'label'   => __('Background Type'),
                                'options' => $this->getBackgroundType()
                            ]
                        ]
                    );
                    
                    $bg_image_inner_container_2->addChildren(
                        'secondary_image',
                        'image',
                        [
                            'sortOrder'       => 30,
                            'key'             => 'secondary_image',
                            'templateOptions' => [
                                'label' => __('Secondary Image')
                            ],
                            'hideExpression' => 'model.secondary_background_type!="image"'
                        ]
                    );

                    $bg_image_inner_container_2->addChildren(
                        'secondary_video_url',
                        'text',
                        [
                            'sortOrder'       => 40,
                            'key'             => 'secondary_video_url',
                            'templateOptions' => [
                                'label' => __('Video URL'),
                            ],
                            'hideExpression' => 'model.secondary_background_type!="video_url"'
                        ]
                    );

                    $bg_image_inner_container_2->addChildren(
                        'secondary_youtube_id',
                        'text',
                        [
                            'sortOrder'       => 50,
                            'key'             => 'secondary_youtube_id',
                            'templateOptions' => [
                                'label' => __('Youtube Video ID'),
                                'note'  => 'For example the Video ID for https://www.youtube.com/watch?v=<strong>HPan7HtIYOw</strong> is <strong>HPan7HtIYOw</strong>'
                            ],
                            'hideExpression' => 'model.secondary_background_type!="youtube"'
                        ]
                    );

                    $bg_image_inner_container_2->addChildren(
                        'secondary_vimeo_id',
                        'text',
                        [
                            'sortOrder'       => 60,
                            'key'             => 'secondary_vimeo_id',
                            'templateOptions' => [
                                'label' => __('Vimeo Video ID'),
                                'note'  => 'For example the Video ID for https://player.vimeo.com/video/<strong>156767727</strong> is <strong>156767727</strong>'
                            ],
                            'hideExpression' => 'model.secondary_background_type!="vimeo"'
                        ]
                    );
                // End Secondary Image

                // List of images
                $bg_image_inner_container_3 = $bg_image_container->addContainerGroup(
                    'bg_image_inner_container_3',
                    [
                        'sortOrder' => 10,
                        'templateOptions' => [
                            'label' => __('Image list'),
                            'collapsible' => true
                        ]
                    ]
                );

                $image_list = $bg_image_inner_container_3->addChildren(
                    'image_list',
                    'dynamicRows',
                    [
                        'key'             => 'image_list',
                        'sortOrder'       => 10,
                        'templateOptions' => [
                            'displayIndex' => true,
                            'collapsible' => true,
                            'rows' => 1
                        ]
                    ]
                );
                    $image_list->addChildren(
                        'image_item',
                        'image',
                        [
                            'sortOrder'       => 10,
                            'key'             => 'image',
                            'templateOptions' => [
                                'label'   => __('Image')
                            ]
                        ]
                    );

                    $image_list->addChildren(
                        'image_title',
                        'text',
                        [
                            'sortOrder'       => 20,
                            'key'             => 'image_title',
                            'templateOptions' => [
                                'label'   => __('Title')
                            ]
                        ]
                    );

                    $image_list->addChildren(
                        'image_subtitle',
                        'text',
                        [
                            'sortOrder'       => 30,
                            'key'             => 'image_subtitle',
                            'templateOptions' => [
                                'label'   => __('Subtitle')
                            ]
                        ]
                    );

                    $image_list->addChildren(
                        'image_content',
                        'editor',
                        [
                            'sortOrder'       => 40,
                            'key'             => 'image_content',
                            'templateOptions' => [
                                'label'   => __('Content')
                            ]
                        ]
                    );

                    $image_list->addChildren(
                        'image_subcontent',
                        'editor',
                        [
                            'sortOrder'       => 50,
                            'key'             => 'image_subcontent',
                            'templateOptions' => [
                                'label'   => __('Sub-Content')
                            ]
                        ]
                    );

                    $image_list->addChildren(
                        'image_cta_type',
                        'select',
                        [
                            'sortOrder'       => 60,
                            'key'             => 'image_cta_type',
                            'templateOptions' => [
                                'label'   => __('CTA Type'),
                                'options' => $this->getSlideLinkType()
                            ]
                        ]
                    );

                    $image_list->addChildren(
                        'image_cta_link',
                        'text',
                        [
                            'sortOrder'       => 70,
                            'key'             => 'image_cta_link',
                            'templateOptions' => [
                                'label'   => __('CTA Link')
                            ]
                        ]
                    );

                    $image_list->addChildren(
                        'image_cta_text',
                        'text',
                        [
                            'sortOrder'       => 80,
                            'key'             => 'image_cta_text',
                            'templateOptions' => [
                                'label'   => __('CTA Text')
                            ]
                        ]
                    );
                // End List of images
            // End Background/Image

        return $tab;
    }
    
    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareLayoutTab()
    {
        // Items tab
        $tab = $this->addTab(
            'tab_layout',
            [
                'sortOrder'       => 0,
                'templateOptions' => [
                    'label' => __('Layout')
                ]
            ]
        );

            // Layout
            $layout_container = $tab->addContainerGroup(
                'layout_container',
                [
                    'sortOrder' => 80,
                    'templateOptions' => [
                        'sortOrder' => 70,
                        'label' => __('Layout Type'),
                        'collapsible' => false
                    ]
                ]
            );

                $layout_container->addChildren(
                    'layout_type',
                    'select',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'layout_type',
                        'defaultValue'    => 'content',
                        'templateOptions' => [
                            'label'   => __('Layout Type'),
                            'options' => $this->getBlockLayoutTypes()
                        ]
                    ]
                );
            // End Layout

        return $tab;
    }

    /**
     * Returns a list of layout types for a given block.
     * Examples include: Banner layout types (Content, Masthead, Header etc.)
     * and Content Listing types (Blog, Staff, News etc.)
     * 
     * @return array
     */
    public function getBlockLayoutTypes()
    {
        return [
            // [
            //     'label' => __('Content'),
            //     'value' => 'content'
            // ]
        ];
    }

    /**
     * Returns a list of layout types for a given content-item.
     * Examples include: Banner layout types (Content, Masthead, Header etc.)
     * and Content Listing types (Blog, Staff, News etc.)
     * 
     * @return array
     */
    public function getItemLayoutTypes()
    {
        return [
            // [
            //     'label' => __('Content'),
            //     'value' => 'content'
            // ]
        ];
    }

    /**
     * @return array
     */
    public function getContainerTypeOptions()
    {
        return [
            [
                'label' => __('Fixed'),
                'value' => 'fixed'
            ],
            [
                'label' => __('Fluid'),
                'value' => 'fluid'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getTextAlignOptions()
    {
        return [
            [
                'label' => __('Left'),
                'value' => 'start'
            ],
            [
                'label' => __('Center'),
                'value' => 'centre'
            ],
            [
                'label' => __('Right'),
                'value' => 'right'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getAlignOptions()
    {
        return [
            [
                'label' => __('Start'),
                'value' => 'start'
            ],
            [
                'label' => __('Center'),
                'value' => 'centre'
            ],
            [
                'label' => __('End'),
                'value' => 'end'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getSizeOptions()
    {
        return [
            [
                'label' => __('Small'),
                'value' => 'small'
            ],
            [
                'label' => __('Medium'),
                'value' => 'medium'
            ],
            [
                'label' => __('Large'),
                'value' => 'large'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getJustifyOptions()
    {
        return [
            [
                'label' => __('Start'),
                'value' => 'start'
            ],
            [
                'label' => __('Centre'),
                'value' => 'centre'
            ],
            [
                'label' => __('End'),
                'value' => 'end'
            ],
            [
                'label' => __('Space Between'),
                'value' => 'space-between'
            ],
            [
                'label' => __('Space Around'),
                'value' => 'space-around'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getContentAlignOptions()
    {
        return [
            [
                'label' => __('Left'),
                'value' => 'left'
            ],
            [
                'label' => __('Centre'),
                'value' => 'centre'
            ],
            [
                'label' => __('Right'),
                'value' => 'right'
            ]
        ];
    }
}