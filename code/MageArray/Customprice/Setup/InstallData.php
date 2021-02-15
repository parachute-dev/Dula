<?php

namespace MageArray\Customprice\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Class InstallData
 * @package MageArray\Customprice\Setup
 */
class InstallData implements InstallDataInterface
{

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    // @codingStandardsIgnoreStart
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        // @codingStandardsIgnoreEnd
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $isRowLabels = $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'row_labels', 'attribute_id');
        if (!is_numeric($isRowLabels)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'row_labels',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Row Labels',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 2,
                ]
            );
        }

        $isColumnLabels = $eavSetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'column_labels',
            'attribute_id'
        );
        if (!is_numeric($isColumnLabels)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'column_labels',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Column Labels',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 3,
                ]
            );
        }

        $isPricemin = $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'pricemin', 'attribute_id');
        if (!is_numeric($isPricemin)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'pricemin',
                [
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Price Min',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'MageArray\Customprice\Model\Attribute\Source\Globaloption',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 5,
                ]
            );
        }

        $isIncludeBaseprice = $eavSetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'include_baseprice',
            'attribute_id'
        );

        if (!is_numeric($isIncludeBaseprice)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'include_baseprice',
                [
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Include Base Price',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'MageArray\Customprice\Model\Attribute\Source\Globaloption',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 6,
                ]
            );
        }

        $isDisplayAsDropdown = $eavSetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'display_as_dropdown',
            'attribute_id'
        );
        if (!is_numeric($isDisplayAsDropdown)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'display_as_dropdown',
                [
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Display as a Dropdown',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'MageArray\Customprice\Model\Attribute\Source\Globaloption',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 7,
                ]
            );
        }

        $isCsvDelimiter = $eavSetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'csv_delimiter',
            'attribute_id'
        );
        if (!is_numeric($isCsvDelimiter)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'csv_delimiter',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'CSV delimiter',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 9,
                ]
            );
        }

        $isMaxError = $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'max_error', 'attribute_id');
        if (!is_numeric($isMaxError)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'max_error',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Max Error',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 10,
                ]
            );
        }

        $isMinError = $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'min_error', 'attribute_id');
        if (!is_numeric($isMinError)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'min_error',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Min Error',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 11,
                ]
            );
        }

        $isMaxMinDefaultDisplay = $eavSetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'max_min_default_display',
            'attribute_id'
        );
        if (!is_numeric($isMaxMinDefaultDisplay)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'max_min_default_display',
                [
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Display Max-Min Value By Default',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'MageArray\Customprice\Model\Attribute\Source\Globaloption',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 12,
                ]
            );
        }

        $isNotFoundSizeMsg = $eavSetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'not_found_size_msg',
            'attribute_id'
        );
        if (!is_numeric($isNotFoundSizeMsg)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'not_found_size_msg',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Not Found Size Message',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 13,
                ]
            );
        }

        $isCsvPriceMarkupType = $eavSetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'csv_price_markup_type',
            'attribute_id'
        );
        if (!is_numeric($isCsvPriceMarkupType)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'csv_price_markup_type',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Markup Type',
                    'input' => 'select',
                    'class' => '',
                    'source' => 'MageArray\Customprice\Model\Attribute\Source\Markuptype',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 14,
                ]
            );
        }

        $isCsvPriceMarkupValue = $eavSetup->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'csv_price_markup_value',
            'attribute_id'
        );
        if (!is_numeric($isCsvPriceMarkupValue)) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'csv_price_markup_value',
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Markup Value',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                    'unique' => false,
                    'apply_to' => '',
                    'system' => 1,
                    'group' => 'CSV Pricing (Override Global Settings)',
                    'sort_order' => 15,
                ]
            );
        }
    }
}
