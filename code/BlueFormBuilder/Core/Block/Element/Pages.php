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

class Pages extends \BlueFormBuilder\Core\Block\Element
{
	/**
	 * @return string
	 */
	public function getAdditionalStyleHtml()
	{
		$styleHtml = parent::getAdditionalStyleHtml();
		$styleHtml .= parent::getTabsStyleHtml();
		$element   = $this->getElement();
		$styles    = [];

		$indicator = $element->getData('indicator');

		if ($indicator == 'tabs') {
			$styles['background-color'] = $this->getStyleColor($element->getData('heading_background_color'));
			$styleHtml .= $this->getStyles('.bfb-pages .mgz-tabs-nav', $styles);

			$styles = [];
			$styles['background-color'] = $this->getStyleColor($element->getData('heading_border_color'));
			$styleHtml .= $this->getStyles('.bfb-pages .mgz-tabs-nav:before', $styles);

			if (!$element->getData('enable_box_shadow')) {
				$styles = [];
				$styles['box-shadow'] = 'none';
				$styleHtml .= $this->getStyles('.bfb-pages', $styles);
			}
		}

		if ($indicator == 'progress' || $indicator == 'circles' || $indicator == 'connector') {
			$styles = [];
			$styles['background-color'] = $this->getStyleColor($element->getData('indicator_color'));
			$styleHtml .= $this->getStyles([
				'.bfb-page-indicator .bfb-page-indicator-page.mgz-active .bfb-page-indicator-page-number',
				'.bfb-page-indicator-page-progress-wrap .bfb-page-indicator-page-progress'
			], $styles);

			if ($indicator == 'connector') {
				$styles = [];
				$styles['border-top-color'] = $this->getStyleColor($element->getData('indicator_color'));
				$styleHtml .= $this->getStyles('.bfb-page-indicator .bfb-page-indicator-page.mgz-active .bfb-page-indicator-page-triangle', $styles);
			}
		}

		$styles = [];
		$styles['color']            = $this->getStyleColor($element->getData('next_button_color'));
		$styles['background-color'] = $this->getStyleColor($element->getData('next_button_background_color'));
		$styleHtml .= $this->getStyles('.bfb-pages .action.action-next', $styles);

		$styles = [];
		$styles['color']            = $this->getStyleColor($element->getData('next_button_hover_color'));
		$styles['background-color'] = $this->getStyleColor($element->getData('next_button_hover_background_color'));
		$styleHtml .= $this->getStyles('.bfb-pages .action.action-next:hover', $styles);

		$styles = [];
		$styles['color']            = $this->getStyleColor($element->getData('prev_button_color'));
		$styles['background-color'] = $this->getStyleColor($element->getData('prev_button_background_color'));
		$styleHtml .= $this->getStyles('.bfb-pages .action.action-prev', $styles);

		$styles = [];
		$styles['color']            = $this->getStyleColor($element->getData('prev_button_hover_color'));
		$styles['background-color'] = $this->getStyleColor($element->getData('prev_button_hover_background_color'));
		$styleHtml .= $this->getStyles('.bfb-pages .action.action-prev:hover', $styles);

		return $styleHtml;
	}
}