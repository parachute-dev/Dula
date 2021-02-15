<?php
namespace Parachute\PageBuilderExtension\Data\Element;

/**
 * Used for Enumerable elements (e.g. - a list of banners, content-items etc.)
 * Inherits from our base-block.
 */

class ParachuteEnumerableElement extends \Parachute\PageBuilderExtension\Data\Element\ParachuteElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
        parent::prepareForm();
        $this->prepareItemsTab();
        $this->prepareCarouselTab();

        // EXTENDING TABS
        // NOTE: If you're extending a tab of the parent class you don't need to call it
        // separately here - so long as it's called already somehow by the parent class
        // Having the method here that calls parent::prepareGeneralTab() is enough!
        // $this->prepareGeneralTab();

        return $this;
    }

    // EXTENDING TABS
    // NOTE: You don't need to call this in prepareForm() if it is already called by the parent
    // prepareGeneralTab() is a good example of extending a tab that already exists.
    // Obviously, be wary of the field names etc.
    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    // public function prepareGeneralTab()
    // {
    // 	$general = parent::prepareGeneralTab();

    //     $general->addChildren(
    //         'general_title',
    //         'text',
    //         [
    //             'sortOrder'       => 10,
    //             'key'             => 'general_title',
    //             'defaultValue'    => 'Default Title',
    //             'templateOptions' => [
    //                 'label' => __('Title')
    //             ]
    //         ]
    //     );

    //     return $general;
    // }

    // OVERRIDING TABS
    // NOTE: If you want to stop a tab being instantiated then just don't call
    // parent::prepareContentTab() as you normally would in that preparation method
    // Not doing so either "removes" that tab or creates it entirely new if you wish to do so
    public function prepareContentTab()
    {
        // Do nothing
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareItemsTab()
    {
		// Items tab
        $tab = $this->addTab(
            'tab_items',
            [
                'sortOrder'       => 0,
                'templateOptions' => [
                    'label' => __('Items')
                ]
            ]
        );

            // Items
        $tab->addChildren(
            'row_limit',
            'number',
            [
                'key'             => 'row_limit',
                'sortOrder'       => 10,
                'defaultValue' => 3,
                'templateOptions' => [
                    'label' => 'Items Per Row',
                ]
            ]
        );

        $items = $tab->addChildren(
            'slides',
            'dynamicRows',
            [
                'key'             => 'items',
                'sortOrder'       => 20,
                'templateOptions' => [
                    'displayIndex' => true
                ]
            ]
        );
                // Active
        $active_container = $items->addContainer(
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
                'defaultValue'    => true,
                'templateOptions' => [
                    'label' => __('Active?'),
                ]
            ]
        );

				// Title
        $title_container = $items->addContainer(
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
        $subtitle_container = $items->addContainer(
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
        $subcontent_container = $items->addContainer(
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
        $content_container = $items->addContainer(
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

				// Footer Title
        $footer_container = $items->addContainer(
            'footer_container',
            [
                'sortOrder'       => 55,
                'templateOptions' => [
                    'label'       => __('Footer'),
                    'collapsible' => true
                ]
            ]
        );

        $footer_container->addChildren(
            'footer_title',
            'text',
            [
                'sortOrder'       => 10,
                'key'             => 'footer_title',
                'templateOptions' => [
                    'label' => __('Footer Title'),
                    'rows'  => 2
                ]
            ]
        );
				// End Footer Title

				// Call to action
        $link_container = $items->addContainer(
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
            'link_type',
            'select',
            [
                'sortOrder'       => 10,
                'key'             => 'cta_type',
                'defaultValue'    => 'full',
                'templateOptions' => [
                    'label'   => __('CTA Type'),
                    'options' => $this->getSlideLinkType()
                ]
            ]
        );

        $link_inner_container_1->addChildren(
            'slide_target',
            'toggle',
            [
                'sortOrder'       => 20,
                'key'             => 'cta_target',
                'templateOptions' => [
                    'label'   => __('Open Slide Link In New Window'),
                ]
            ]
        );

        $link_inner_container_1->addChildren(
            'slide_link',
            'text',
            [
                'sortOrder'       => 20,
                'key'             => 'cta_link',
                'templateOptions' => [
                    'label' => __('Call to Action Link')
                ]
            ]
        );

        $link_inner_container_1->addChildren(
            'cta_link_text',
            'text',
            [
                'sortOrder'       => 30,
                'key'             => 'cta_link_text',
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

                        // $link_inner_container_2->addChildren(
                        //     'subcontent_link_type',
                        //     'select',
                        //     [
                        //         'sortOrder'       => 10,
                        //         'key'             => 'subcontent_cta_type',
                        //         'defaultValue'    => 'full',
                        //         'templateOptions' => [
                        //             'label'   => __('CTA Type'),
                        //             'options' => $this->getSlideLinkType()
                        //         ]
                        //     ]
                        // );

        $link_inner_container_2->addChildren(
            'subcontent_slide_target',
            'toggle',
            [
                'sortOrder'       => 20,
                'key'             => 'subcontent_cta_target',
                'templateOptions' => [
                    'label'   => __('Open Slide Link In New Window'),
                ]
            ]
        );

        $link_inner_container_2->addChildren(
            'subcontent_slide_link',
            'text',
            [
                'sortOrder'       => 20,
                'key'             => 'subcontent_cta_link',
                'templateOptions' => [
                    'label' => __('Call to Action Link')
                ]
            ]
        );

        $link_inner_container_2->addChildren(
            'subcontent_cta_link_text',
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

				// Background/image
        $bg_image_container = $items->addContainerGroup(
            'bg_image_container',
            [
              'sortOrder' => '60',
              'templateOptions' => [
               'sortOrder' => 60,
               'label' => __('Images'),
               'collapsible' => true,
               'className' => 'mgz-width100'
           ]
       ]
   );
                // Background/image
        $bg_image_secondary_container = $items->addContainerGroup(
            'bg_image_secondary_container',
            [
              'sortOrder' => '60',
              'templateOptions' => [
               'sortOrder' => 60,
               'label' => __('Secondary Images'),
               'collapsible' => true,
               'className' => 'mgz-width100'
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

        $bg_image_secondary_inner_container_1 = $bg_image_secondary_container->addContainerGroup(
            'bg_image_secondary_inner_container_1',
            [
                'sortOrder' => 10,
                'templateOptions' => [
                    'label' => __('Secondary Image')
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
                    'options' => $this->getBackgroundType(),
                    'className' => 'mgz-width100'
                ]
            ]
        );

        $bg_image_secondary_inner_container_1->addChildren(
            'secondary_image',
            'image',
            [
                'sortOrder'       => 20,
                'key'             => 'secondary_image',
                'templateOptions' => [
                    'label' => __('Secondary Image')
                ],
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

                    // List of images

        $background_colour = $items->addContainerGroup(
            'background_colour_container',
            [
                'sortOrder' => '60',
                'templateOptions' => [
                    'sortOrder' => 60,
                    'label' => __('Background Colour'),
                    'collapsible' => true,
                    'className' => 'mgz-width100'
                ]
            ]
        );


        $background_colour->addChildren(
            'background_color',
            'select',
            [
                'sortOrder'       => 10,
                'key'             => 'background_color',
                'defaultValue'    => 'transparent',
                'templateOptions' => [
                    'label'   => __('Background Colour'),
                    'options' => $this->getBackgroundColor()
                ]
            ]
        );



        $content_width = $items->addContainerGroup(
            'content_width_container',
            [
                'sortOrder' => '60',
                'templateOptions' => [
                    'sortOrder' => 60,
                    'label' => __('Content Width'),
                    'collapsible' => true,
                    'className' => 'mgz-width100'
                ]
            ]
        );


        $content_width->addChildren(
            'content_width',
            'select',
            [
                'sortOrder'       => 10,
                'key'             => 'content_width',
                'defaultValue'    => 'full',
                'templateOptions' => [
                    'label'   => __('Content Width'),
                    'options' => $this->getContentWidth()
                ]
            ]
        );

        $banner_style = $items->addContainerGroup(
            'banner_style_container',
            [
                'sortOrder' => '60',
                'templateOptions' => [
                    'sortOrder' => 60,
                    'label' => __('Banner Style'),
                    'collapsible' => true,
                    'className' => 'mgz-width100'
                ]
            ]
        );


        $banner_style->addChildren(
            'banner_style',
            'select',
            [
                'sortOrder'       => 10,
                'key'             => 'banner_style',
                'defaultValue'    => 'full',
                'templateOptions' => [
                    'label'   => __('Banner Style'),
                    'options' => $this->getBannerStyle()
                ]
            ]
        );


                // Video 
        $video_container = $items->addContainerGroup(
            'video_container',
            [
              'sortOrder' => 70,
              'templateOptions' => [
               'sortOrder' => 70,
               'label' => __('Video'),
               'collapsible' => true
           ]
       ]
   );

        $video_container->addChildren(
            'video_url',
            'text',
            [
                'sortOrder'       => 10,
                'key'             => 'video_url',
                'defaultValue'    => 'full',
                'templateOptions' => [
                    'label'   => __('Video URL')
                ]
            ]
        );
                // End of Video

				// Position/Alignment
        $position_container = $items->addContainerGroup(
            'position_container',
            [
              'sortOrder' => 70,
              'templateOptions' => [
               'sortOrder' => 70,
               'label' => __('Position, Alignment & Size'),
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
                    'label'   => __('Horizontal Positioning'),
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
                    'label'   => __('Vertical Positioning'),
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
                'defaultValue'    => 'left',
                'templateOptions' => [
                    'label'   => __('Text Alignment'),
                    'options' => $this->getTextAlignOptions()
                ]
            ]
        );


                // End Position/Alignment

				// Layout
        $layout_container = $items->addContainerGroup(
            'layout_container',
            [
              'sortOrder' => 80,
              'templateOptions' => [
               'sortOrder' => 70,
               'label' => __('Layout'),
               'collapsible' => true
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
                    'options' => $this->getItemLayoutTypes()
                ]
            ]
        );
				// End Layout

				// Ordering/Remove
        $ordering_container = $items->addContainerGroup(
            'ordering_container',
            [
                'sortOrder' => 90
            ]
        );

        $ordering_container->addChildren(
            'delete',
            'actionDelete',
            [
                'sortOrder' => 10,
                'className' => 'mgz-width10'
            ]
        );

        $ordering_container->addChildren(
            'item_order',
            'text',
            [
                'sortOrder'       => 20,
                'key'             => 'item_order',
                'className'       => 'mgz-width20',
                'templateOptions' => [
                    'element'     => 'Magezon_Builder/js/form/element/dynamic-rows/position',
                    'placeholder' => __('Item Order')
                ]
            ]
        );
                // End Ordering/Remove

        return $tab;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareCarouselTab($sortOrder = 80)
    {
        $carousel = $this->addTab(
            'tab_carousel',
            [
                'sortOrder'       => $sortOrder,
                'templateOptions' => [
                    'label' => __('Carousel Options')
                ]
            ]
        );

            // Is the carousel active?
        $carousel->addChildren(
            'is_carousel_active',
            'toggle',
            [
                'sortOrder'       => 10,
                'key'             => 'is_carousel_active',
                'templateOptions' => [
                    'label' => __('Is Carousel Active?')
                ]
            ]
        );
            // End is the carousel active?

        $colors = $carousel->addTab(
            'colors',
            [
                'sortOrder'       => 10,
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
            'color',
            'color',
            [
                'sortOrder'       => 10,
                'key'             => 'owl_color',
                'templateOptions' => [
                    'label' => __('Color')
                ]
            ]
        );

        $color1->addChildren(
            'background_color',
            'color',
            [
                'sortOrder'       => 20,
                'key'             => 'owl_background_color',
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

        $color1 = $hover->addContainerGroup(
            'color1',
            [
                'sortOrder' => 10
            ]
        );

        $color1->addChildren(
            'hover_color',
            'color',
            [
                'sortOrder'       => 10,
                'key'             => 'owl_hover_color',
                'templateOptions' => [
                    'label' => __('Color')
                ]
            ]
        );

        $color1->addChildren(
            'hover_background_color',
            'color',
            [
                'sortOrder'       => 20,
                'key'             => 'owl_hover_background_color',
                'templateOptions' => [
                    'label' => __('Background Color')
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

        $color1 = $active->addContainerGroup(
            'color1',
            [
                'sortOrder' => 10
            ]
        );

        $color1->addChildren(
            'active_color',
            'color',
            [
                'sortOrder'       => 10,
                'key'             => 'owl_active_color',
                'templateOptions' => [
                    'label' => __('Color')
                ]
            ]
        );

        $color1->addChildren(
            'active_background_color',
            'color',
            [
                'sortOrder'       => 20,
                'key'             => 'owl_active_background_color',
                'templateOptions' => [
                    'label' => __('Background Color')
                ]
            ]
        );

        $container3 = $carousel->addContainerGroup(
            'container3',
            [
                'sortOrder' => 40
            ]
        );

        $container3->addChildren(
            'nav',
            'toggle',
            [
                'key'             => 'owl_nav',
                'sortOrder'       => 10,
                'defaultValue'    => false,
                'templateOptions' => [
                    'label' => __('Navigation Buttons')
                ]
            ]
        );

        $container3->addChildren(
            'nav_position',
            'select',
            [
                'key'             => 'owl_nav_position',
                'sortOrder'       => 20,
                'defaultValue'    => 'center_split',
                'templateOptions' => [
                    'label'   => __('Navigation Position'),
                    'options' => $this->getNavigationPosition()
                ]
            ]
        );

        $container3->addChildren(
            'nav_size',
            'select',
            [
                'key'             => 'owl_nav_size',
                'sortOrder'       => 30,
                'defaultValue'    => 'normal',
                'templateOptions' => [
                    'label'   => __('Navigation Size'),
                    'options' => $this->getNavigationSize()
                ]
            ]
        );

        $container4 = $carousel->addContainerGroup(
            'container4',
            [
                'sortOrder' => 50
            ]
        );

        $container4->addChildren(
            'dots',
            'toggle',
            [
                'key'             => 'owl_dots',
                'sortOrder'       => 10,
                'defaultValue'    => true,
                'templateOptions' => [
                    'label' => __('Dots Navigation')
                ]
            ]
        );

        $container4->addChildren(
            'dots_insie',
            'toggle',
            [
                'key'             => 'owl_dots_insie',
                'sortOrder'       => 20,
                'templateOptions' => [
                    'label' => __('Dots Inside')
                ]
            ]
        );

        $container4->addChildren(
            'dots_speed',
            'number',
            [
                'key'             => 'owl_dots_speed',
                'sortOrder'       => 30,
                'templateOptions' => [
                    'label' => __('Dots Speed')
                ]
            ]
        );

        $container5 = $carousel->addContainerGroup(
            'container5',
            [
                'sortOrder' => 60
            ]
        );

        $container5->addChildren(
            'lazyload',
            'toggle',
            [
                'key'             => 'owl_lazyload',
                'sortOrder'       => 10,
                'defaultValue'    => false,
                'templateOptions' => [
                    'label' => __('Lazyload')
                ]
            ]
        );

        $container5->addChildren(
            'loop',
            'toggle',
            [
                'key'             => 'owl_loop',
                'sortOrder'       => 20,
                'defaultValue'    => false,
                'templateOptions' => [
                    'label' => __('Loop')
                ]
            ]
        );

        $container5->addChildren(
            'margin',
            'number',
            [
                'key'             => 'owl_margin',
                'sortOrder'       => 30,
                'defaultValue'    => '0',
                'templateOptions' => [
                    'label' => __('Margin'),
                    'note'  => __('margin-right(px) on item.')
                ]
            ]
        );

        $container6 = $carousel->addContainerGroup(
            'container6',
            [
                'sortOrder' => 70
            ]
        );

        $container6->addChildren(
            'autoplay',
            'toggle',
            [
                'key'             => 'owl_autoplay',
                'sortOrder'       => 10,
                'templateOptions' => [
                    'label' => __('Auto Play')
                ]
            ]
        );

        $container6->addChildren(
            'autoplay_hover_pause',
            'toggle',
            [
                'key'             => 'owl_autoplay_hover_pause',
                'sortOrder'       => 20,
                'templateOptions' => [
                    'label' => __('Pause on Mouse Hover')
                ]
            ]
        );

        $container6->addChildren(
            'autoplay_timeout',
            'text',
            [
                'key'             => 'owl_autoplay_timeout',
                'defaultValue'    => '5000',
                'sortOrder'       => 30,
                'templateOptions' => [
                    'label' => __('Auto Play Timeout')
                ]
            ]
        );

        $carousel->addChildren(
            'animation_in',
            'select',
            [
                'sortOrder'       => 90,
                'key'             => 'owl_animation_in',
                'className'       => 'mgz-inner-widthauto',
                'templateOptions' => [
                    'templateUrl' => 'Magezon_Builder/js/templates/form/element/animation-style.html',
                    'element'     => 'Magezon_Builder/js/form/element/animation-in',
                    'label'       => __('Animation In')
                ]
            ]
        );

        $carousel->addChildren(
            'animation_out',
            'select',
            [
                'sortOrder'       => 90,
                'key'             => 'owl_animation_out',
                'className'       => 'mgz-inner-widthauto',
                'templateOptions' => [
                    'templateUrl' => 'Magezon_Builder/js/templates/form/element/animation-style.html',
                    'element'     => 'Magezon_Builder/js/form/element/animation-out',
                    'label'       => __('Animation Out')
                ]
            ]
        );

        return $carousel;
    }

    public function prepareLayoutTab()
    {
        parent::prepareLayoutTab();
    }

    /**
     * @return array
     */
    public function getCaptionType()
    {
        $headingType = parent::getHeadingType();
        $headingType[] = [
            'label' => 'Div',
            'value' => 'div'
        ];
        return $headingType;
    }

    /**
     * @return array
     */
    public function getBackgroundType()
    {
        return [
            [
                'label' => __('Image'),
                'value' => 'image'
            ],
            [
                'label' => __('Youtube'),
                'value' => 'youtube'
            ],
            [
                'label' => __('Vimeo'),
                'value' => 'vimeo'
            ]
            ,
            [
                'label' => __('Video URL'),
                'value' => 'video_url'
            ]            
        ];
    }

    public function getBackgroundPosition()
    {
        return [
            [
                'label' => __('Center'),
                'value' => 'center'
            ],
            [
                'label' => __('Left'),
                'value' => 'youtube'
            ],
            [
                'label' => __('Right'),
                'value' => 'vimeo'
            ]          
        ];
    }

    /**
     * @return array
     */
    public function getSlideLinkType()
    {
        return [
            [
                'label' => __('Button'),
                'value' => 'button'
            ],
            [
                'label' => __('Full Slide'),
                'value' => 'full'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getHoverEffect()
    {
        return [
            [
                'label' => __('None'),
                'value' => ''
            ],
            [
                'label' => __('Zoom In'),
                'value' => 'zoomin'
            ],
            [
                'label' => __('Lift Up'),
                'value' => 'liftup'
            ],
            [
                'label' => __('Zoom Out'),
                'value' => 'zoomout'
            ]
        ];
    }

    public function getVideoAspectRatio()
    {
        return [
            [
                'label' => '3:2',
                'value' => '32'
            ],
            [
                'label' => '4:3',
                'value' => '43'
            ],
            [
                'label' => '16:9',
                'value' => '169'
            ],
            [
                'label' => '21:9',
                'value' => '219'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        return [
            'items' => [
                [
                    'background_type' => 'image',
                    'image'           => 'mgzbuilder/no_image2.png',
                    'heading'         => 'Slide1',
                    'heading_type'    => 'h2',
                    'position'        => 1
                ]
            ]
        ];
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

        ];
    }

    /**
     * @return array
     */
    public function getActiveOptions()
    {
        return [
            [
                'label' => __('Active'),
                'value' => 'active'
            ],
            [
                'label' => __('Inactive'),
                'value' => 'inactive'
            ]
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

    /**
     * @return array
     */
    public function getAlignOptions()
    {
        return [
            [
                'label' => __('Top'),
                'value' => 'start'
            ],
            [
                'label' => __('Centre'),
                'value' => 'centre'
            ],
            [
                'label' => __('Bottom'),
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
    public function getBackgroundColor()
    {
        return [
            [
                'label' => __('Solid White'),
                'value' => 'white'
            ],
            [
                'label' => __('Transparent White'),
                'value' => 'white-overlay'
            ],            
            [
                'label' => __('Transparent'),
                'value' => 'transparent'
            ],
            [
                'label' => __('Transparent - White Text'),
                'value' => 'transparent-white-text'
            ],            
            [
                'label' => __('Solid Black'),
                'value' => 'black'
            ],
            [
                'label' => __('Black Overlay'),
                'value' => 'black-overlay'
            ],
        ];
    }

    /**
     * @return array
     */
    public function getContentWidth()
    {
        return [
            [
                'label' => __('50%'),
                'value' => 'half'
            ],
            [
                'label' => __('100%'),
                'value' => 'full'
            ],
            [
                'label' => __('60%'),
                'value' => 'sixty'
            ],


            [
                'label' => __('33%'),
                'value' => 'third'
            ],

        ];
    }

    /**
     * @return array
     */
    public function getBannerStyle()
    {
        return [
            [
                'label' => __('One'),
                'value' => 'one'
            ],
            [
                'label' => __('Two'),
                'value' => 'two'
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
                'label' => __('Left'),
                'value' => 'start'
            ],
            [
                'label' => __('Centre'),
                'value' => 'centre'
            ],
            [
                'label' => __('Right'),
                'value' => 'end'
            ],

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