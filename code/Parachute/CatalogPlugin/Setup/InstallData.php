<?php
namespace Parachute\CustomerAttributes\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config;
use Magento\Customer\Model\Customer;

class InstallData implements InstallDataInterface
{
    // Fields
    private $_eavSetupFactory;
    private $_eavConfig;

    // Constructor
	public function __construct(
        EavSetupFactory $eavSetupFactory,
        Config $eavConfig)
	{
		$this->_eavSetupFactory = $eavSetupFactory;
		$this->_eavConfig = $eavConfig;
    }
    
    // Install method
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
	{
        $eavSetup = $this->_eavSetupFactory->create(['setup' => $setup]);
		$eavSetup->addAttribute(
			\Magento\Customer\Model\Customer::ENTITY,
			'customer_image',
			[
				'type'         => 'varchar',
				'label'        => 'Customer Image',
				'input'        => 'image',
				'required'     => false,
				'visible'      => true,
				'user_defined' => true,
				'position'     => 999,
				'system'       => 0,
            ]);

            // Get our image attribute
            $_customerImageAttr = $this->_eavConfig->getAttribute(Customer::ENTITY, 'customer_image');

            // more used_in_forms ['adminhtml_checkout','adminhtml_customer','adminhtml_customer_address','customer_account_edit','customer_address_edit','customer_register_address']
            $_customerImageAttr->setData(
                'used_in_forms',
                ['adminhtml_customer']
            );

            $_customerImageAttr->save();
    }
}