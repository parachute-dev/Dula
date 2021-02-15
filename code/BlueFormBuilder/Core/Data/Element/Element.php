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

namespace BlueFormBuilder\Core\Data\Element;

class Element extends AbstractElement
{
    /**
     * @return array
     */
    public function getConfig()
    {
    	$config = parent::getConfig();
    	if (!isset($config['templateUrl']) || !$config['templateUrl']) {
    		$config['templateUrl'] = 'BlueFormBuilder_Core/js/templates/builder/control.html';
    	}
    	return $config;
    }
}