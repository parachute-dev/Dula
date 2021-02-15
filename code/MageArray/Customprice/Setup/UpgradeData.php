<?php
namespace MageArray\Customprice\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * Category setup factory
     *
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * EAV setup factory
     *
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var UpgradeWidgetData
     */
    private $upgradeWidgetData;

    /**
     * @var UpgradeWebsiteAttributes
     */
    private $upgradeWebsiteAttributes;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $applyCsvType = $eavSetup->getAttribute(\Magento\Catalog\Model\Product::ENTITY, 'apply_csv_type', 'attribute_id');
            if (!is_numeric($applyCsvType)) {
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'apply_csv_type',
                    [
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Apply CSV Type',
                        'input' => 'select',
                        'class' => '',
                        'source' => 'MageArray\Customprice\Model\Attribute\Source\ApplyCsvType',
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
                        'sort_order' => 0,
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            $isCsvCsvLabel = $eavSetup->getAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'csv_csv_label',
                'attribute_id'
            );
            if (!is_numeric($isCsvCsvLabel)) {
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'csv_csv_label',
                    [
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Option Wise CSV Label',
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
                        'sort_order' => 16,
                    ]
                );
            }

            $isCsvPriceUnit = $eavSetup->getAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'csv_price_unit',
                'attribute_id'
            );
            if (!is_numeric($isCsvPriceUnit)) {
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'csv_price_unit',
                    [
                        'type' => 'varchar',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Unit',
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
                        'sort_order' => 4,
                    ]
                );
            }

            $isCsvDisplayAllDropdown = $eavSetup->getAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'csv_display_all_dropdown',
                'attribute_id'
            );
            if (!is_numeric($isCsvDisplayAllDropdown)) {
                $eavSetup->addAttribute(
                    \Magento\Catalog\Model\Product::ENTITY,
                    'csv_display_all_dropdown',
                    [
                        'type' => 'int',
                        'backend' => '',
                        'frontend' => '',
                        'label' => 'Display all range value',
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
                        'sort_order' => 8,
                    ]
                );
            }
        }
        $setup->endSetup();
    }
}
