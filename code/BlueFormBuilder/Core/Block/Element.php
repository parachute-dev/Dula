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

namespace BlueFormBuilder\Core\Block;

use \Magento\Framework\App\ObjectManager;

class Element extends \Magezon\Builder\Block\Element
{
    /**
     * @return array
     */
	public function getWrapperClasses()
	{
		$classes = parent::getWrapperClasses();

        $element = $this->getElement();
        
        $classes[] = 'bfb-element';

        if ($labelPosition = $element->getLabelPosition()) {
            $classes[] = 'bfb-element-label-' . $labelPosition;
        }

        if ($labelAlignment = $element->getLabelAlignment()) {
            $classes[] = 'bfb-element-label-align-' . $labelAlignment;
        }

        if ($element->getRequired()) {
            $classes[] = 'required';
        }

        if ($element->getShowIcon() && $element->getIcon()) {
			$classes[] = 'bfb-element-icon-' . $element->getIconPosition();
        }

        if ($element->hasData('hidden') && $element->getHidden()) {
            $classes[] = 'bfb-element-hidden';
        }

		return $classes;
	}

    /**
     * @return array
     */
	public function getInnerClasses()
	{
		$classes = parent::getInnerClasses();

		$classes[] = 'bfb-element-inner';

		return $classes;
	}

    /**
     * @param  int  $min
     * @param  int  $max
     * @param  int $step
     * @return array
     */
    public function getRange($min, $max, $step = 1)
    {
        $options = [];
        for ($i = 0; $i <= $max; $i) {
            if ($i >= $min) {
                $label = $i;
                if ($i<10) {
                    $label = '0' . $label;
                }
                $options[] = [
                    'label' => $label,
                    'value' => $i
                ];
                $i = $i + $step;
            } else {
                $i++;
            }
        }
        return $options;
    }

    /**
     * @return string
     */
	public function getElHtmlId()
	{
		$element = $this->getElement();
        return 'bfb-control-' . $element->getId();
	}

    /**
     * @return string
     */
    public function getElemName()
    {
        $elemName = $this->getElement()->getData('elem_name');
        if (!$elemName) {
            $elemName = $this->getElement()->getData('id');
        }
        return $elemName;
    }
    
    /**
     * @return string
     */
    public function getAdditionalStyleHtml()
    {
        $styleHtml = '';
        $element   = $this->getElement();

        if ($labelWidth = (float)$element->getLabelWidth()) {
            $styles = [];
            $styles['width'] = $labelWidth . '%';
            $styleHtml .= $this->getStyles('.bfb-element-label', $styles);

            if ($element->getLabelPosition() == 'left' || $element->getLabelPosition() == 'right') {
                $styles = [];
                $styles['width'] = (100 - $labelWidth) . '%';
                $styleHtml .= $this->getStyles('.bfb-element-control', $styles);
            }
        }

        if ($controlWidth = $element->getControlWidth()) {
            $styles = [];
            $styles['width'] = $this->getStyleProperty($controlWidth);
            if ($controlWidth == 'auto') {
                $styles['display'] = 'inline-block';
            }
            $styleHtml .= $this->getStyles('.bfb-element-control-inner', $styles);
        }

        if ($element->getShowIcon() && $element->getIcon()) {
            $styles = [];
            $styles['color'] = $this->getStyleColor($element->getData('icon_color'));
            $styleHtml .= $this->getStyles('.bfb-element-icon', $styles);
        }

        return $styleHtml;
    }

    /**
     * @return \BlueFormBuilder\Core\Helper\Data
     */
    public function getFormDataHelper()
    {
        return ObjectManager::getInstance()->get(\BlueFormBuilder\Core\Helper\Data::class);
    }
}