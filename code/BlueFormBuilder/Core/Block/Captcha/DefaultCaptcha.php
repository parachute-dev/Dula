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

namespace BlueFormBuilder\Core\Block\Captcha;

class DefaultCaptcha extends \Magento\Captcha\Block\Captcha\DefaultCaptcha
{
	protected $_template = 'BlueFormBuilder_Core::captcha.phtml';

    /**
     * Renders captcha HTML (if required)
     *
     * @return string
     */
    protected function _toHtml()
    {
        $this->getCaptchaModel()->generate();
        return $this->fetchView($this->getTemplateFile());
    }
}