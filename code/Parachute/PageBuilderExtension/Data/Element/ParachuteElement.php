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
                    'block_carousel',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'block_carousel',
                        'defaultValue'    => false,
                        'templateOptions' => [
                            'label' => __('Carousel?'),
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
                        'defaultValue'    => 'centre',
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
                        'defaultValue'    => 'centre',
                        'templateOptions' => [
                            'label'   => __('Content Alignment'),
                            'options' => $this->getAlignOptions()
                        ]
                    ]
                );

     

                $position_container->addChildren(
                    'text_align',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'text_align',
                        'defaultValue'    => 'centre',
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

        return $tab;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareContentTab()
    {

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