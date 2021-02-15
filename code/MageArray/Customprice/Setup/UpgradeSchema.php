<?php
namespace MageArray\Customprice\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package MageArray\Customprice\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();
        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $setup->getConnection()->addColumn(
                $setup->getTable('magearray_csvprice'),
                'option_sku',
                [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 50,
                        'nullable' => true,
                        'comment' => 'Option Sku'
                    ]
            );
            $setup->endSetup();
        }
    }
}
