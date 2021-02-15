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

namespace BlueFormBuilder\Core\Model\Element;

class YesNoElement extends \BlueFormBuilder\Core\Model\Element
{
	public function prepareValue($val)
	{
		if ($val) {
			$value = __('No');
			if ($val) {
				$value = __('Yes');
			}
			$this->setValue($value);
	        $this->setHtmlValue($value);
	        $this->setEmailHtmlValue($value);
	    }
	}
}