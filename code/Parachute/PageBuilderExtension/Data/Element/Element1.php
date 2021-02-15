<?php
namespace Parachute\PageBuilderExtension\Data\Element;

// class Element1 extends \Magezon\PageBuilder\Data\Element\Slider
class Element1 extends \Parachute\PageBuilderExtension\Data\Element\ParachuteElement
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
					'defaultValue'    => 'Default Title',
					'templateOptions' => [
						'label' => __('Title')
	                ]
	            ]
	        );

    	return $general;
    }
}