<?php namespace Parachute\PageBuilderExtension\Plugin; 
class DefaultItemPlugin { 

public function afterGetItemData( \Magento\Checkout\CustomerData\AbstractItem $subject, $result, \Magento\Quote\Model\Quote\Item $item) { 


$data['product_sub_name'] = $item->getProduct()->getAttributeText('product_sub_name'); return \array_merge( $result, $data ); } }