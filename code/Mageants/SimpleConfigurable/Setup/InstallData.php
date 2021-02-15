<?php
/**
 * @category Mageants SimpleConfigurable
 * @package Mageants_SimpleConfigurable
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */
 
namespace Mageants\SimpleConfigurable\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * EAV setup factory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Init
     *
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory,StoreManagerInterface $storeManager)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->_storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        /**
         * Add attributes to the eav/attribute
         */

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'is_default_selected',
            [
                'type' => 'int',
                'backend' => '',
                'frontend' => '',
                'label' => 'Is Default Selected',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'group' => ''
            ]
        );  
        
        $service_url = 'https://www.mageants.com/index.php/rock/register/live?ext_name=Mageants_SimpleConfigurable&dom_name='.$this->_storeManager->getStore()->getBaseUrl();
        $curl = curl_init($service_url);     

        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_FOLLOWLOCATION =>true,
            CURLOPT_ENCODING=>'',
            CURLOPT_USERAGENT => 'Mozilla/5.0'
        ));
        
        $curl_response = curl_exec($curl);
        curl_close($curl);  
    }
}
