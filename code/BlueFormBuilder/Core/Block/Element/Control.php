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

namespace BlueFormBuilder\Core\Block\Element;

class Control extends \BlueFormBuilder\Core\Block\Element
{
    /**
     * @param  string $html 
     * @return string            
     */
    public function getElementHtml($html)
    {
    	$dataHelper    = $this->getFormDataHelper();
    	$elementId     = $this->getElHtmlId();
    	$element       = $this->getElement();
    	$label         = $element->getLabel();
    	$labelPosition = $element->getLabelPosition();
    	$description   = $dataHelper->filter($element->getDescription());
    	$tooltip       = $dataHelper->filter($element->getTooltip());

    	$result = '';
    	if (($label || $tooltip) && $labelPosition !== 'below') {
    		$result .= '<div class="bfb-element-label">';
				if ($label) {
					$result .= '<label for="' . $elementId . '"><span>' . $label . '</span></label>';
				}
				if ($tooltip) {
					$result .= '<div class="bfb-element-tooltip">';
						$result .= '<div class="bfb-element-tooltip-action action-help"><i class="fas mgz-fa-question-circle"></i></div>';
						$result .= '<div class="bfb-element-tooltip-content">' . $tooltip . '</div>';
					$result .= '</div>';
				}
			$result .= '</div>';
    	}

    	$result .= '<div class="bfb-element-control">';
	    	$result .= '<div class="bfb-element-control-inner" data-bind="scope: \'' . $elementId . '\'">';
	    	$result .= $html;
	    $result .= '</div>';
	    $result .= '<div class="bfb-error" data-single-error="true"></div>';
	    if ($description) {
	    	$result .= '<div class="bfb-element-description">' . $description . '</div>';
	    }
		$result .= '</div>';
		if (($label || $tooltip) && $labelPosition === 'below') {
			$result .= '<div class="bfb-element-label">';
				if ($label) {
					$result .= '<label for="' . $elementId . '"><span>' . $label . '</span></label>';
				}
				if ($tooltip) {
					$result .= '<div class="bfb-element-tooltip">';
						$result .= '<div class="bfb-element-tooltip-action action-help"><i class="fas mgz-fa-question-circle"></i></div>';
						$result .= '<div class="bfb-element-tooltip-content">' . $tooltip . '</div>';
					$result .= '</div>';
				}
			$result .= '</div>';
		}

		return parent::getElementHtml($result);
    }
}