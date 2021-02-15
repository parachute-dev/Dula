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

class Magento2Captcha extends Element
{
    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareAppearanceTab()
    {
        $tab = parent::prepareAppearanceTab();

            $container3 = $tab->getElements()->searchById('container3');
            if ($container3) {
                $tab->removeElement('container3');
            }

        return $tab;
    }

    public function getDefaultValues()
    {
        return [
            'label' => 'Please type the letters and numbers below'
        ];
    }
}