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

class ChoiceMatrix extends Control
{
	/**
	 * @return string
	 */
	public function getAdditionalStyleHtml()
	{
		$styleHtml = parent::getAdditionalStyleHtml();
		$element   = $this->getElement();

		$styles = [];
		$styles['color'] = $this->getStyleColor($element->getData('heading_color'));
		$styles['background-color'] = $this->getStyleColor($element->getData('heading_background_color'));
		$styleHtml .= $this->getStyles('thead th', $styles);

		if ($element->getData('cell_border_width')) {
			$styles = [];
			$styles['border-width'] = $this->getStyleProperty($element->getData('cell_border_width'));
			$styles['border-style'] = $element->getData('cell_border_style');
			$styleHtml .= $this->getStyles([
				'table th',
				'table td'
			], $styles);
			$styles = [];
			$styles['border-color'] = $this->getStyleColor($element->getData('heading_cell_border_color'));
			$styleHtml .= $this->getStyles([
				'thead th'
			], $styles);
			$styles = [];
			$styles['border-color'] = $this->getStyleColor($element->getData('cell_border_color'));
			$styleHtml .= $this->getStyles([
				'tbody td'
			], $styles);
		}

		$styles = [];
		$styles['color']            = $this->getStyleColor($element->getData('odd_rows_color'));
		$styles['background-color'] = $this->getStyleColor($element->getData('odd_rows_background_color'));
		$styleHtml .= $this->getStyles('tbody tr:nth-child(odd)', $styles);

		$styles = [];
		$styles['color']            = $this->getStyleColor($element->getData('even_rows_color'));
		$styles['background-color'] = $this->getStyleColor($element->getData('even_rows_background_color'));
		$styleHtml .= $this->getStyles('tbody tr:nth-child(even)', $styles);

		return $styleHtml;
	}
}