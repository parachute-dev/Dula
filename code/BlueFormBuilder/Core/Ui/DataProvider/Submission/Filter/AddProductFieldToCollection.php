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

namespace BlueFormBuilder\Core\Ui\DataProvider\Submission\Filter;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

class AddProductFieldToCollection implements AddFilterToCollectionInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
	public function __construct(
		\Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
	) {
		$this->productCollectionFactory = $productCollectionFactory;
	}

    /**
     * {@inheritdoc}
     */
    public function addFilter(Collection $collection, $field, $condition = null)
    {
    	$productCollection = $this->productCollectionFactory->create();
    	$productCollection->addAttributeToSelect('name');
    	$productCollection->addAttributeToFilter('name', $condition);
        $collection->addFieldToFilter('main_table.product_id', ['in' => $productCollection->getAllIds()]);
    }
}
