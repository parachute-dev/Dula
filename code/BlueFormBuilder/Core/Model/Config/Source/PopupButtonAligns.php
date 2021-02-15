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

namespace BlueFormBuilder\Core\Model\Config\Source;

class PopupButtonAligns implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * To option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'inline',
                'label' => __('Inline')
            ],
            [
                'value' => 'left',
                'label' => __('Left')
            ],
            [
                'value' => 'bottom-left',
                'label' => __('Bottom Left')
            ],
            [
                'value' => 'right',
                'label' => __('Right')
            ],
            [
                'value' => 'bottom-right',
                'label' => __('Bottom Right')
            ],
        ];
    }
}
