<?php
namespace Parachute\PageBuilderExtension\Data\Element;

class Newsletter extends \Parachute\PageBuilderExtension\Data\Element\ParachuteEnumerableElement
{
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
            [
                'label' => __('Content'),
                'value' => 'content'
            ],
            [
                'label' => __('Masthead'),
                'value' => 'masthead'
            ],
            [
                'label' => __('Header'),
                'value' => 'header'
            ],
            [
                'label' => __('Asymmetric'),
                'value' => 'asymmetric'
            ]
        ];
    }
}