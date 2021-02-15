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

class Text extends Control
{
	/**
	 * @return array
	 */
	public function getElementValidate()
	{
		$validate = [];
		$element  = $this->getElement();
		if ($element->getData('required')) $validate['required'] = true;
		$min      = $element->getData('min');
		$max      = $element->getData('max');
		$limitBy  = $element->getData('limit_by');
		if (($validation = $element->getData('validation')) && ($validation !== 'empty')) $validate[$validation] = true;
		if ($min || $max) {
		    if ($limitBy === 'words') {
		        if ($min)  $validate['min-words'] = $attrs['data-min-words'] = $min;
		        if ($max && ($max > $min || !$min)) $validate['max-words'] = $attrs['data-max-words'] =  $max;
		    }
		}
		return $validate;
	}

	/**
	 * @return array
	 */
	public function getControlAttributes()
	{
		$element              = $this->getElement();
		$attrs['id']          = $this->getElHtmlId();
		$attrs['class']       = 'bfb-control';
		$attrs['name']        = $this->getElemName();
		$attrs['placeholder'] = $this->escapeHtmlAttr($element->getData('placeholder'));
		$min                  = $element->getData('min');
		$max                  = $element->getData('max');
		$limitBy              = $element->getData('limit_by');
		$validate             = [];
		if ($element->getData('autofocus')) $attrs['autofocus'] = 'true';
		if ($element->getData('autocomplete')) {
			$attrs['autocomplete'] = 'on';
		} else {
			$attrs['autocomplete'] = 'off';
		}
		if ($min || $max) {
		    if ($limitBy === 'words') {
		        if ($min)  $validate['min-words'] = $attrs['data-min-words'] = $min;
		        if ($max && ($max > $min || !$min)) $validate['max-words'] = $attrs['data-max-words'] =  $max;
		    }
		    if ($limitBy === 'characters') {
		        $attrs['class'] .= ' validate-length';

		        if ($min) {
					$attrs['class'] .= ' minimum-length-' . $min;
		            $attrs['data-min-chars'] = $min;
		        }

		        if ($max && ($max > $min || !$min)) {
					$attrs['class'] .= ' maximum-length-' . $max;
		            $attrs['data-max-chars'] = $max;
		        }
		    }
		}
		$showCount           = $element->getData('show_count');
		$limitMsg            = $element->getData('limit_message');
		$defaultValue        = $element->getData('default_value');
		$attrs['value']      = $this->escapeHtmlAttr((strpos($defaultValue, '[')===false) ? $defaultValue : '');
		$attrs['data-value'] = $this->escapeHtmlAttr($defaultValue);
		if ($element->getData('readonly')) $attrs['readonly'] = 'true';
		return $attrs;
	}

    /**
     * @return string
     */
	public function getJsLayout()
	{
		$element      = $this->getElement();
		$elementId    = $this->getElHtmlId();
		$min          = $element->getData('min');
		$max          = $element->getData('max');
		$limitBy      = $element->getData('limit_by');
		$defaultValue = $element->getData('default_value');
		$autoSuggest  = $this->getAutoSuggest();
		$result = [
			'component' => 'BlueFormBuilder_Core/js/form/element/text',
			'value'     => $defaultValue ? $defaultValue : '',
			'max'       => ($max > $min) ? $max : '',
			'limitType' => $limitBy,
			'element'   => "#" . $elementId,
			'options'   => $autoSuggest ? $autoSuggest : '[]',
			'ajaxUrl'   => $this->getUrl('blueformbuilder/section/load')
		];
		return json_encode($result);
	}

	/**
	 * @return string
	 */
	public function getAutoSuggest()
	{
		$element = $this->getElement();
		return str_replace('\r', '', json_encode( explode("\n", $element->getData('auto_suggest')) ));
	}
}