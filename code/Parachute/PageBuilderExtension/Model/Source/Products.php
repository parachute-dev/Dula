<?php
/**
 * Magezon
 *
 * This source file is subject to the Magezon Software License, which is available at https://www.magezon.com/license
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to https://www.magezon.com for more information.
 *
 * @category  Magezon
 * @package   Magezon_PageBuilder
 * @copyright Copyright (C) 2019 Magezon (https://www.magezon.com)
 */

 // Gets a collection of Products for use as options with with a uiSelect element
 // Ref: \Magezon\PageBuilder\Model\Source\Categories

namespace Parachute\PageBuilderExtension\Model\Source;

use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\DB\Helper as DbHelper;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\Product as ProductModel;

class Products
{
    /**#@+
     * Category tree cache id
     */
    const PRODUCT_TREE_ID = 'PARACHUTE_MAGEZON_BUILDERS_PRODUCT_TREE';

    /**
     * @var CacheInterface
     */
    private $_cacheManager;

    /**
     * @var ProductCollectionFactory
     */
    private $_productCollectionFactory;

    /**
     * @var DbHelper
     */
    private $_dbHelper;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    private $_coreHelper;

    /**
     * @param ProductCollectionFactory $productCollectionFactory 
     * @param DbHelper                  $dbHelper                  
     * @param \Magezon\Core\Helper\Data $coreHelper                
     */
    public function __construct(
        ProductCollectionFactory $productCollectionFactory,
        DbHelper $dbHelper,
        \Magezon\Core\Helper\Data $coreHelper
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_dbHelper = $dbHelper;
        $this->_coreHelper = $coreHelper;
    }

	public function getConfig()
	{
		return $this->getOptions();
	}

    // Gets a collection of Products for use as options with with a uiSelect element
    // Namely the ProductPicker block
	public function getOptions()
	{
		$productTree = $this->getCacheManager()->load(self::PRODUCT_TREE_ID);
        if ($productTree) {
            return $this->_coreHelper->unserialize($productTree);
        }

		$collection = $this->_productCollectionFactory->create();
		$collection->addAttributeToSelect('name');
		$options    = [];
		foreach ($collection as $product) {
			$productLabel = $this->getSpaces($product['level']) . '(ID:' . $product->getId() . ') ' . $product->getName();
            $product['label'] = $productLabel;
			$options[] = [
				'value'       => $product->getId(),
				'label'       => $productLabel,
				'short_label' => '(ID:' . $product->getId() . ')' . $product->getName()
			];
		}

		$this->getCacheManager()->save(
            $this->_coreHelper->serialize($options),
            self::PRODUCT_TREE_ID,
            [
                \Magento\Framework\App\Cache\Type\Block::CACHE_TAG
            ]
        );

		return $options;
	}

    protected function getSpaces($number)
    {
        $s = '';
        for($i = 0; $i < $number; $i++) {
            $s .= '_ ';
        }
        return $s;
    }

    /**
     * Retrieve cache interface
     *
     * @return CacheInterface
     * @deprecated 101.0.3
     */
    private function getCacheManager()
    {
        if (!$this->_cacheManager) {
            $this->_cacheManager = ObjectManager::getInstance()
                ->get(CacheInterface::class);
        }
        return $this->_cacheManager;
    }
}