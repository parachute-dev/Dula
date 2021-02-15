<?php

namespace Parachute\AttributeAddon\Plugin;

use Magento\Quote\Model\Quote\Item;

class DefaultItem
{
    public function aroundGetItemData($subject, \Closure $proceed, Item $item)
    {
        $data = $proceed($item);
        $product = $item->getProduct();

        $atts = [
            "product_sub_name" => $product->getAttributeText('product_sub_name'),
        ];

        return array_merge($data, $atts);
    }
}