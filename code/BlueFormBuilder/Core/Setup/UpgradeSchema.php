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

namespace BlueFormBuilder\Core\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'mgz_blueformbuilder_form'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mgz_blueformbuilder_form')
        )->addColumn(
            'form_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Form ID'
        )->addColumn(
            'name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Name'
        )->addColumn(
            'identifier',
            Table::TYPE_TEXT,
            100,
            ['nullable' => false],
            'Form Identifier'
        )->addColumn(
            'profile',
            Table::TYPE_TEXT,
            '64M',
            [],
            'Short Code'
        )->addColumn(
            'enable_notification',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Enable Notification'
        )->addColumn(
            'sender_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Sender Name'
        )->addColumn(
            'sender_email',
            Table::TYPE_TEXT,
            255,
            [],
            'Sender Email'
        )->addColumn(
            'reply_to',
            Table::TYPE_TEXT,
            255,
            [],
            'Reply To'
        )->addColumn(
            'recipients',
            Table::TYPE_TEXT,
            255,
            [],
            'Send Email(s) To'
        )->addColumn(
            'recipients_bcc',
            Table::TYPE_TEXT,
            255,
            [],
            'BBC'
        )->addColumn(
            'email_subject',
            Table::TYPE_TEXT,
            255,
            [],
            'Email Subject'
        )->addColumn(
            'email_body',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Email Body'
        )->addColumn(
            'attach_files',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Attach File Uploads to Emails'
        )->addColumn(
            'enable_customer_notification',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Enable Customer Notification'
        )->addColumn(
            'customer_sender_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Custom Sender Name'
        )->addColumn(
            'customer_sender_email',
            Table::TYPE_TEXT,
            255,
            [],
            'Custom Sender Email'
        )->addColumn(
            'customer_reply_to',
            Table::TYPE_TEXT,
            255,
            [],
            'Customer Reply To'
        )->addColumn(
            'customer_email_subject',
            Table::TYPE_TEXT,
            255,
            [],
            'Customer Email Subject'
        )->addColumn(
            'customer_email_body',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Customer Email Body'
        )->addColumn(
            'customer_attach_files',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Attach File Uploads to Emails'
        )->addColumn(
            'is_active',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Is Form Active'
        )->addColumn(
            'disable_form_page',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Disable Form Page'
        )->addColumn(
            'show_toplink',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Show in Top Links'
        )->addColumn(
            'position',
            Table::TYPE_TEXT,
            255,
            [],
            'Position'
        )->addColumn(
            'meta_title',
            Table::TYPE_TEXT,
            255,
            [],
            'Meta Title'
        )->addColumn(
            'meta_keywords',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Form Meta Keywords'
        )->addColumn(
            'meta_description',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Form Meta Description'
        )->addColumn(
            'js_on_pageload',
            Table::TYPE_TEXT,
            '64k',
            [],
            'On Page Load'
        )->addColumn(
            'js_before_submit',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Before Submit'
        )->addColumn(
            'js_after_submit',
            Table::TYPE_TEXT,
            '64k',
            [],
            'After Submit'
        )->addColumn(
            'disable_multiple',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Disable multiple submissions from same device'
        )->addColumn(
            'disable_multiple_condition',
            Table::TYPE_TEXT,
            255,
            [],
            'Disable Condition'
        )->addColumn(
            'disable_multiple_message',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Message when disabled'
        )->addColumn(
            'disable_multiple_fields',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Disable Multiple Fields'
        )->addColumn(
            'disable_after_nos',
            Table::TYPE_TEXT,
            255,
            [],
            'Disable form when it reaches X submissions'
        )->addColumn(
            'redirect_to',
            Table::TYPE_TEXT,
            255,
            [],
            'Redirect on Submit'
        )->addColumn(
            'redirect_delay',
            Table::TYPE_TEXT,
            255,
            [],
            'Redirect X seconds after form submit'
        )->addColumn(
            'submission_prefix',
            Table::TYPE_TEXT,
            255,
            [],
            'Submission Prefix'
        )->addColumn(
            'page_layout',
            Table::TYPE_TEXT,
            255,
            [],
            'Page Layout'
        )->addColumn(
            'custom_classes',
            Table::TYPE_TEXT,
            255,
            [],
            'Custom Classes'
        )->addColumn(
            'custom_css',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Custom CSS'
        )->addColumn(
            'success_message',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Success Message'
        )->addColumn(
            'success_message_header',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Success Message Header'
        )->addColumn(
            'success_message_footer',
            Table::TYPE_TEXT,
            '64k',
            [],
            'Success Message Footer'
        )->addColumn(
            'success_message_style',
            Table::TYPE_TEXT,
            255,
            [],
            'Success Message Style'
        )->addColumn(
            'success_message_heading_color',
            Table::TYPE_TEXT,
            255,
            [],
            'Success Message Heading Color'
        )->addColumn(
            'success_message_heading_background_color',
            Table::TYPE_TEXT,
            255,
            [],
            'Success Message Heading Background Color'
        )->addColumn(
            'success_message_heading_border_color',
            Table::TYPE_TEXT,
            255,
            [],
            'Success Message Heading Border Color'
        )->addColumn(
            'bfb_form_key',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'BFB Form Key'
        )->addColumn(
            'enable_autosave',
            Table::TYPE_SMALLINT,
            null,
            ['default' => 1],
            'Auto Save Form Process'
        )->addColumn(
            'width',
            Table::TYPE_TEXT,
            255,
            [],
            'Width'
        )->addColumn(
            'creation_time',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Form Creation Time'
        )->addColumn(
            'update_time',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Form Modification Time'
        )->setComment(
            'BlueFormBuilder Form Table'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mgz_blueformbuilder_form_store'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mgz_blueformbuilder_form_store')
        )->addColumn(
            'form_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'primary' => true],
            'Form ID'
        )->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addIndex(
            $installer->getIdxName('mgz_blueformbuilder_form_store', ['store_id']),
            ['store_id']
        )->addForeignKey(
            $installer->getFkName('mgz_blueformbuilder_form_store', 'form_id', 'mgz_blueformbuilder_form', 'form_id'),
            'form_id',
            $installer->getTable('mgz_blueformbuilder_form'),
            'form_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('mgz_blueformbuilder_form_store', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE
        )->setComment(
            'BlueFormBuilder Form To Store Linkage Table'
        );
        $installer->getConnection()->createTable($table);

        $customerGroupTable = $setup->getConnection()->describeTable($setup->getTable('customer_group'));
        $customerGroupIdType = $customerGroupTable['customer_group_id']['DATA_TYPE'] == 'int'
            ? Table::TYPE_INTEGER : $customerGroupTable['customer_group_id']['DATA_TYPE'];

        /**
         * Create table 'mgz_blueformbuilder_form_customer_group'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mgz_blueformbuilder_form_customer_group')
        )->addColumn(
            'form_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'primary' => true],
            'Form Id'
        )->addColumn(
            'customer_group_id',
            $customerGroupIdType,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Customer Group ID'
        )->addIndex(
            $installer->getIdxName('mgz_blueformbuilder_form_customer_group', ['customer_group_id']),
            ['customer_group_id']
        )->addIndex(
            $installer->getIdxName('mgz_blueformbuilder_form_customer_group', ['form_id']),
            ['form_id']
        )->addForeignKey(
            $installer->getFkName('mgz_blueformbuilder_form_customer_group', 'form_id', 'mgz_blueformbuilder_form', 'form_id'),
            'form_id',
            $installer->getTable('mgz_blueformbuilder_form'),
            'form_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('mgz_blueformbuilder_form_customer_group', 'customer_group_id', 'customer_group', 'customer_group_id'),
            'customer_group_id',
            $installer->getTable('customer_group'),
            'customer_group_id',
            Table::ACTION_CASCADE
        )->setComment(
            'FormBuilder Form Customer Group'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mgz_blueformbuilder_submission'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mgz_blueformbuilder_submission')
        )->addColumn(
            'submission_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Submission ID'
        )->addColumn(
            'form_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'primary' => true],
            'Form Id'
        )->addColumn(
            'customer_id',
            Table::TYPE_INTEGER,
            null,
            [],
            'Customer Id'
        )->addColumn(
            'increment_id',
            Table::TYPE_TEXT,
            255,
            [],
            'Increment Id'
        )->addColumn(
            'product_id',
            Table::TYPE_TEXT,
            255,
            [],
            'Product Id'
        )->addColumn(
            'sender_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Sender Name'
        )->addColumn(
            'sender_email',
            Table::TYPE_TEXT,
            255,
            [],
            'Sender Email'
        )->addColumn(
            'reply_to',
            Table::TYPE_TEXT,
            255,
            [],
            'Reply To'
        )->addColumn(
            'recipients',
            Table::TYPE_TEXT,
            255,
            [],
            'Rcipients'
        )->addColumn(
            'recipients_bcc',
            Table::TYPE_TEXT,
            255,
            [],
            'Recipients BCC'
        )->addColumn(
            'email_subject',
            Table::TYPE_TEXT,
            255,
            [],
            'Email Subject'
        )->addColumn(
            'email_body',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Email Body'
        )->addColumn(
            'customer_sender_name',
            Table::TYPE_TEXT,
            255,
            [],
            'Custom Sender Name'
        )->addColumn(
            'customer_sender_email',
            Table::TYPE_TEXT,
            255,
            [],
            'Customer Sender Email'
        )->addColumn(
            'customer_reply_to',
            Table::TYPE_TEXT,
            255,
            [],
            'Customer Reply To'
        )->addColumn(
            'customer_email_subject',
            Table::TYPE_TEXT,
            255,
            [],
            'Customer Email Subject'
        )->addColumn(
            'customer_recipients',
            Table::TYPE_TEXT,
            255,
            [],
            'Customer Recipients'
        )->addColumn(
            'customer_email_body',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Customer Email Body'
        )->addColumn(
            'form_params',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Form Params'
        )->addColumn(
            'remote_ip',
            Table::TYPE_TEXT,
            16,
            [],
            'Customer IP'
        )->addColumn(
            'remote_ip_long',
            Table::TYPE_BIGINT,
            null,
            ['default' => 0],
            'Customer IP converted to long integer format'
        )->addColumn(
            'submission_content',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Content'
        )->addColumn(
            'admin_submission_content',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Admin Submission Content'
        )->addColumn(
            'params',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Params'
        )->addColumn(
            'post',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Form Post'
        )->addColumn(
            'processed_params',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Processed Params'
        )->addColumn(
            'elements',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Elements'
        )->addColumn(
            'submitted_page',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Submitted from Page'
        )->addColumn(
            'store_id',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Store ID'
        )->addColumn(
            'condition_emails',
            Table::TYPE_TEXT,
            255,
            [],
            'Conditional Emails'
        )->addIndex(
            $installer->getIdxName('mgz_blueformbuilder_submission', ['store_id']),
            ['store_id']
        )->addColumn(
            'brower',
            Table::TYPE_TEXT,
            255,
            [],
            'Brower'
        )->addColumn(
            'values',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Values'
        )->addColumn(
            'creation_time',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Submission Creation Time'
        )->addColumn(
            'read',
            Table::TYPE_TEXT,
            '255',
            [],
            'Read'
        )->addColumn(
            'update_time',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Submission Modification Time'
        )->addColumn(
            'submission_hash',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Submission Hash'
        )->addColumn(
            'send_count',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default' => '0'],
            'Send Count'
        )->addColumn(
            'customer_send_count',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'default' => '0'],
            'Customer Send Count'
        )->addColumn(
            'is_active',
            Table::TYPE_SMALLINT,
            null,
            ['default' => '0'],
            'Is Submission Active'
        )->addColumn(
            'admin_notification',
            Table::TYPE_SMALLINT,
            null,
            ['default' => '0'],
            'Is Admin Notification'
        )->addColumn(
            'enable_trackback_page',
            Table::TYPE_SMALLINT,
            null,
            ['default' => 1],
            'Enable Trackback Page'
        )->addColumn(
            'customer_notification',
            Table::TYPE_SMALLINT,
            null,
            ['default' => '0'],
            'Is Customer Notification'
        )->addForeignKey(
            $installer->getFkName('mgz_blueformbuilder_submission', 'store_id', 'store', 'store_id'),
            'store_id',
            $installer->getTable('store'),
            'store_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('mgz_blueformbuilder_submission', 'form_id', 'mgz_blueformbuilder_form', 'form_id'),
            'form_id',
            $installer->getTable('mgz_blueformbuilder_form'),
            'form_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Form Submission'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mgz_blueformbuilder_submission_file'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mgz_blueformbuilder_submission_file')
        )->addColumn(
            'file_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'File ID'
        )->addColumn(
            'submission_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'primary' => true],
            'Submission ID'
        )->addColumn(
            'form_id',
            Table::TYPE_INTEGER,
            null,
            [],
            'Form ID'
        )->addColumn(
            'element_id',
            Table::TYPE_TEXT,
            '255',
            [],
            'Element ID'
        )->addColumn(
            'file',
            Table::TYPE_TEXT,
            '255',
            [],
            'File'
        )->addColumn(
            'size',
            Table::TYPE_INTEGER,
            null,
            [],
            'Size'
        )->addColumn(
            'mine_type',
            Table::TYPE_TEXT,
            '255',
            [],
            'Mine Type'
        )->addColumn(
            'number_of_downloads',
            Table::TYPE_SMALLINT,
            null,
            ['default' => 0],
            'Number of Downloads'
        )->addColumn(
            'file_hash',
            Table::TYPE_TEXT,
            '2M',
            [],
            'File Hash'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addIndex(
            $installer->getIdxName('mgz_blueformbuilder_submission_file', ['submission_id']),
            ['submission_id']
        )->addForeignKey(
            $installer->getFkName('mgz_blueformbuilder_submission_file', 'submission_id', 'mgz_blueformbuilder_submission', 'submission_id'),
            'submission_id',
            $installer->getTable('mgz_blueformbuilder_submission'),
            'submission_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Submission File'
        );
        $installer->getConnection()->createTable($table);

        /**
         * Create table 'mgz_blueformbuilder_progress'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('mgz_blueformbuilder_form_progress')
        )->addColumn(
            'progress_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Progress ID'
        )->addColumn(
            'form_id',
            Table::TYPE_INTEGER,
            null,
            [],
            'Form ID'
        )->addColumn(
            'visitor_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true],
            'Visitor Id'
        )->addColumn(
            'post',
            Table::TYPE_TEXT,
            '2M',
            [],
            'Form Post'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
            'Creation Time'
        )->addColumn(
            'update_time',
            Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
            'Modification Time'
        )->addIndex(
            $installer->getIdxName(
                'mgz_blueformbuilder_form_progress',
                ['visitor_id', 'form_id'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE
            ),
            ['visitor_id', 'form_id'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
        )->addForeignKey(
            $installer->getFkName('mgz_blueformbuilder_form_progress', 'form_id', 'mgz_blueformbuilder_form', 'form_id'),
            'form_id',
            $installer->getTable('mgz_blueformbuilder_form'),
            'form_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Form Progress'
        );
        $installer->getConnection()->createTable($table);

        $setup->getConnection()->addColumn(
            $installer->getTable('mgz_blueformbuilder_form'),
            'enable_recaptcha',
            [
                'type'    => Table::TYPE_SMALLINT,
                'comment' => 'Enable reCaptcha',
                'default' => 0
            ]
        );

        $installer->endSetup();
    }
}