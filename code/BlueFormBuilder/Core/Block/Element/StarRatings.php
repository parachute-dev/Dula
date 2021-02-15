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

class StarRatings extends Control
{
	/**
	 * @return string
	 */
	public function getAdditionalStyleHtml()
	{
		$styleHtml = parent::getAdditionalStyleHtml();
		$element   = $this->getElement();

		$styles['color'] = $this->getStyleColor($element->getData('star_color'));
		$styleHtml .= $this->getStyles('.review-control-vote:before', $styles);
        // HOVER
		$styles = [];
		$styles['color'] = $this->getStyleColor($element->getData('star_active_color'));
		$styleHtml .= $this->getStyles('.review-control-vote label', $styles, ':hover:before');
		$styleHtml .= $this->getStyles('.review-control-vote label', $styles, ':before');

		return $styleHtml;
	}
}