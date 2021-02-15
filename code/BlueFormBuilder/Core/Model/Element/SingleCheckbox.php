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

class SingleCheckbox extends \BlueFormBuilder\Core\Model\Element
{
	public function prepareValue($val)
	{
		$value = $val;
		if ($val) {
			if ($this->getConfig('checked_value')) {
				$value = $this->getConfig('checked_value');
			}
		} else {
			if ($this->getConfig('unchecked_value')) {
				$value = $this->getConfig('unchecked_value');
			}
		}
		$this->setValue($value);
        $this->setHtmlValue($value);
        $this->setEmailHtmlValue($value);
	}
}