<?php
namespace Parachute\PageBuilderExtension\Data\Element;

class ContentListings extends \Parachute\PageBuilderExtension\Data\Element\ParachuteEnumerableElement
{
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
            [
                'label' => __('Content Block'),
                'value' => 'content-block'
            ],
            [
                'label' => __('Blog'),
                'value' => 'blog'
            ],
            [
                'label' => __('Collections'),
                'value' => 'collections'
            ],
            [
                'label' => __('Quote'),
                'value' => 'quote'
			],
            [
                'label' => __('Team'),
                'value' => 'team'
			],
            [
                'label' => __('Job'),
                'value' => 'job'
            ],
            [
                'label' => __('Testimonial'),
                'value' => 'testimonial'
			],
            [
                'label' => __('Dropdown'),
                'value' => 'dropdown'
			]
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
        parent::getItemLayoutTypes();

        return [

        ];
    }
}