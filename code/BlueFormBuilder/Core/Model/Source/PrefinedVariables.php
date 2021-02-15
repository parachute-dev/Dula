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

namespace BlueFormBuilder\Core\Model\Source;

class PrefinedVariables
{
    /**
     * Get options
     *
     * @return array
     */
    public function getOptions()
    {
    	$variables = [
            [
                'label'   => __('Customer'),
                'options' => $this->getCustomerAttributes()
            ],
            [
                'label'   => __('Page'),
                'options' => $this->getPageAttributes()
            ],
            [
                'label'   => __('Product'),
                'options' => $this->getProductAttributes()
            ]
        ];

        return $variables;
    }

    public function getPageAttributes()
    {
        $attributes = [
            [
                'label' => __('Page Url Key [page.url_key]'),
                'value' => '[page.url_key]'
            ],
            [
                'label' => __('Page Title [page.title]'),
                'value' => '[page.title]'
            ]
        ];

        return $attributes;
    }

    public function getProductAttributes()
    {
        $attributes = [
            [
                'label' => __('Product ID [product.id]'),
                'value' => '[product.id]'
            ],
            [
                'label' => __('Product Name [product.name]'),
                'value' => '[product.name]'
            ],
            [
                'label' => __('Product SKU [product.sku]'),
                'value' => '[product.sku]'
            ],
            [
                'label' => __('Product Price [product.price]'),
                'value' => '[product.price]'
            ],
            [
                'label' => __('Product Special Price [product.special_price]'),
                'value' => '[product.special_price]'
            ],
            [
                'label' => __('Product Short Description [product.short_description]'),
                'value' => '[product.short_description]'
            ],
            [
                'label' => __('Product Url Key [product.url_key]'),
                'value' => '[product.url_key]'
            ],
            [
                'label' => __('Product Price From Date [product.special_from_date]'),
                'value' => '[product.special_from_date]'
            ],
            [
                'label' => __('Special Price To Date [product.special_to_date]'),
                'value' => '[product.special_to_date]'
            ],
            [
                'label' => __('Product New From Date [product.news_from_date]'),
                'value' => '[product.news_from_date]'
            ],
            [
                'label' => __('Special New To Date [product.news_to_date]'),
                'value' => '[product.news_to_date]'
            ]
        ];

        return $attributes;
    }

    public function getCustomerAttributes()
    {
        $attributes = [
            [
                'label' => __('Customer ID [customer.id]'),
                'value' => '[customer.id]'
            ],
            [
                'label' => __('Customer First Name [customer.firstname]'),
                'value' => '[customer.firstname]'
            ],
            [
                'label' => __('Customer Middle Name [customer.middlename]'),
                'value' => '[customer.middlename]'
            ],
            [
                'label' => __('Customer Last Name [customer.lastname]'),
                'value' => '[customer.lastname]'
            ],
            [
                'label' => __('Customer Full Name [customer.fullname]'),
                'value' => '[customer.fullname]'
            ],
            [
                'label' => __('Customer Email [customer.email]'),
                'value' => '[customer.email]'
            ],
            [
                'label' => __('Customer Date of Birth [customer.dob]'),
                'value' => '[customer.dob]'
            ],
            [
                'label' => __('Customer Prefix [customer.prefix]'),
                'value' => '[customer.prefix]'
            ],
            [
                'label' => __('Customer Suffix [customer.suffix]'),
                'value' => '[customer.suffix]'
            ],
            [
                'label' => __('Customer IP [customer.ip]'),
                'value' => '[customer.ip]'
            ],
            [
                'label' => __('Customer Group [customer.group]'),
                'value' => '[customer.group]'
            ],
            [
                'label' => __('Customer Tax/VAT Number [customer.taxvat]'),
                'value' => '[customer.taxvat]'
            ],
            [
                'label' => __('Customer Gender [customer.gender]'),
                'value' => '[customer.gender]'
            ],
            [
                'label' => __('Customer Billing Address [customer.billing_address]'),
                'value' => '[customer.billing_address]'
            ]
        ];

        return $attributes;
    }
}