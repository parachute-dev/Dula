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

namespace BlueFormBuilder\Core\Model;

class DefaultConfigProvider extends \Magezon\Builder\Model\DefaultConfigProvider
{
	/**
	 * @var string
	 */
	protected $_builderArea = 'bfb';

	/**
	 * @return array
	 */
	public function getConfig()
	{
		$config = parent::getConfig();
		$config['profile'] = [
			'builder'           => 'BlueFormBuilder\Core\Block\Builder',
			'home'              => 'https://www.magezon.com/magento-2-form-builder.html?utm_campaign=mgzbuilder&utm_source=mgz_user&utm_medium=backend',
			'templateUrl'       => 'https://www.magezon.com/productfile/blueformbuilder/templates.php',
			'prefinedVariables' => [
				'class' => '\BlueFormBuilder\Core\Model\Source\PrefinedVariables'
			]
		];
		foreach ($config['elements'] as &$element) {
		 	if (isset($element['area']) && in_array('bfb', $element['area'])) {
	 			if (!isset($element['element']) || !$element['element']) {
	 				$element['element'] = 'BlueFormBuilder_Core/js/builder/field';
			 		if (!isset($element['templateUrl']) || !$element['templateUrl']) {
					 	if (isset($element['control']) && $element['control']) {
					 		$element['templateUrl'] = 'BlueFormBuilder_Core/js/templates/builder/control.html';
					 	} else {
					 		$element['templateUrl'] = 'BlueFormBuilder_Core/js/templates/builder/field.html';
					 	}
					}
	 			}
		 	}
		}
		return $config;
	}

	/**
	 * @return array
	 */
	// public function getConfig($cache = true)
	// {
	// 	$config  = parent::getConfig();
	// 	$profile = $this->profileFactory->create();
	// 	$path    = $this->builderHelper->getArrayManager()->findPath('column', $config, 'elements');
	// 	$config['builderClass'] = 'BlueFormBuilder\Core\Block\Builder';
	// 	$config['profile']      = [
	// 		'home' => 'https://www.magezon.com/magento-2-form-builder.html?utm_campaign=mgzbuilder&utm_source=mgz_user&utm_medium=backend',
	// 		'settings' => [
	// 			'class' => 'Magezon\Builder\Data\Modal\Profile'
	// 		],
	// 		'defaultSettings' => $profile->prepareForm()->getFormDefaultValues(),
	// 		'prefinedVariables' => [
	// 			'class' => '\BlueFormBuilder\Core\Model\Source\PrefinedVariables'
	// 		],
	// 		'templates' => [
	// 			'class' => 'BlueFormBuilder\Core\Data\Modal\Templates'
	// 		],
	// 		'editorMode' => true
	// 	];
	// 	foreach ($config['elements'] as &$element) {
	// 	 	if (in_array('bfb', $element['area'])) {
	//  			if (!isset($element['element']) || !$element['element']) {
	//  				$element['element'] = 'BlueFormBuilder_Core/js/builder/field';
	// 		 		if (!isset($element['templateUrl']) || !$element['templateUrl']) {
	// 				 	if (isset($element['control']) && $element['control']) {
	// 				 		$element['templateUrl'] = 'BlueFormBuilder_Core/js/templates/builder/control.html';
	// 				 	} else {
	// 				 		$element['templateUrl'] = 'BlueFormBuilder_Core/js/templates/builder/field.html';
	// 				 	}
	// 				}
	//  			}
	// 	 	}
	// 	}
	// 	return $config;
	// }
}