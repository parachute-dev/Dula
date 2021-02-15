<?php
/**
 * @category Mageants ConfigurableProductWholesale
 * @package Mageants_ConfigurableProductWholesale
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */
namespace Mageants\SimpleConfigurable\Model\Source;

class Reload implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => 'name',
                'label' => __('Name')
            ],
            [
                'value' => 'description',
                'label' => __('Description')
            ],
            [
                'value' => 'short_description',
                'label' => __('Short Description')
            ],
            [
                'value' => 'attributes',
                'label' => __('More Information Block')
            ],
            [
                'value' => 'sku',
                'label' => __('SKU')
            ]
        ];

        return $options;
    }
}
