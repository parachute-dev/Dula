<?php

namespace MageArray\Customprice\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    // @codingStandardsIgnoreStart
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $isTableExists = $installer->tableExists('magearray_csvprice');
        if ($isTableExists != 1) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('magearray_csvprice')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'CSVPRICE ID'
            )->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false, 'unsigned' => true],
                'Product Id'
            )->addColumn(
                'csv_price',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'CSV Price'
            )->addColumn(
                'option_sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                50,
                ['nullable' => true],
                'Option Sku'
            )->addColumn(
                'file_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'File name with path'
            )->addColumn(
                'f_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => true],
                'File name'
            )->addForeignKey(
                $installer->getFkName(
                    'magearray_csvprice',
                    'FK_product_id',
                    'catalog_product_entity',
                    'entity_id'
                ),
                'product_id',
                $installer->getTable('catalog_product_entity'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )->setComment(
                'CSV Price'
            );

            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
