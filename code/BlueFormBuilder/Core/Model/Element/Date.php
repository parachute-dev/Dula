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

class Date extends \BlueFormBuilder\Core\Model\Element
{

	public function prepareGridValue($value)
	{
		if ($value) {
			$builderElement = $this->getBuilderElement();
			$date  = \DateTime::createFromFormat(str_replace(['mm', 'dd', 'yy'], ['m', 'd', 'Y'], $this->getConfig('date_format')), $value);
			$value = date_format($date, 'Y-m-d');
			$value = new \DateTime($value);
		}
		return $value;
	}
	
}