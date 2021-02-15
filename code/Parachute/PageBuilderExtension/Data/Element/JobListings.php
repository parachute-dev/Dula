<?php
namespace Parachute\PageBuilderExtension\Data\Element;

class JobListings extends \Parachute\PageBuilderExtension\Data\Element\ContentListings
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
        parent::prepareForm();

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
}