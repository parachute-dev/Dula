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

class Number extends Control
{
    /**
     * @return string
     */
	public function getJsLayout()
	{
		$element      = $this->getElement();
		$defaultValue = (float)$element->getData('default_value');
		$step         = $element->getData('step');
		$min          = $element->getData('min');
		$max          = $element->getData('max');
		$result = [
			'component' => 'BlueFormBuilder_Core/js/form/element/number',
			'default'   => $defaultValue ? $defaultValue : 0,
			'step'      => $step ? $step : 1,
			'min'       => $min,
			'max'       => $max
		];
		return json_encode($result);
	}
	
	/**
	 * @return string
	 */
	public function getAdditionalStyleHtml()
	{
		$styleHtml = parent::getAdditionalStyleHtml();
		$element   = $this->getElement();

		$styles['color']      = $this->getStyleColor($element->getData('btn_color'));
		$styles['background'] = $this->getStyleColor($element->getData('btn_background_color'));
		$styleHtml .= $this->getStyles('.bfb-element-number-btn', $styles);

        // HOVER
		$styles = [];
		$styles['color']      = $this->getStyleColor($element->getData('btn_hover_color'));
		$styles['background'] = $this->getStyleColor($element->getData('btn_hover_background_color'));
		$styleHtml .= $this->getStyles('.bfb-element-number-btn', $styles, ':hover');

		return $styleHtml;
	}
}