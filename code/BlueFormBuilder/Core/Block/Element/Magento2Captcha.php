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

class Magento2Captcha extends Control
{
	public function getCaptchaHtml()
	{
		$element = $this->getElement();
		$block   = $this->getLayout()->createBlock(\BlueFormBuilder\Core\Block\Captcha\DefaultCaptcha::class);
		$html    = $block->setHtmlId($this->getHtmlId())
		->setImgWidth(200)
		->setImgHeight(50)
		->toHtml();
        return $html;
	}
}