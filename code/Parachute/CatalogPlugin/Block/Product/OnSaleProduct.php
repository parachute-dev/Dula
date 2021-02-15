<?php
/**
 * Parachute
 *
 * NOTICE OF LICENSE
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Parachute
 * @package     Parachute_CatalogPlugin
 * @copyright   Copyright (c) Parachute (https://www.thisisparachute.com/)
 */
namespace Parachute\CatalogPlugin\Block\Product;

use Zend_Db_Expr;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product;
use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Config;
// use Magento\Catalog\Model\Layer;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Pricing\Price\FinalPrice;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Config\Element;
use Magento\Framework\Data\Helper\PostHelper;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Render;
use Magento\Framework\Url\Helper\Data;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\CatalogInventory\Helper\Stock;

/**
 * Class OnSaleProduct
 * @package Parachute\CatalogPlugin\Block\OnSaleProduct
 */
class OnSaleProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    // Fields
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_catalogProductVisibility;

    /**
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    protected $_stockFilter;

    /**
     * Catalog layer
     *
     * @var \Parachute\CatalogPlugin\Model\Layer
     */
    protected $_parachuteCatalogLayer;

    /**
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Stock $stockFilter
     * @param Data $urlHelper
     * @param array $data
     */
    public function __construct(
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        \Parachute\CatalogPlugin\Model\Layer $catalogLayer,
        CategoryRepositoryInterface $catRepo,
        Stock $stockFilter,
        Data $urlHelper,
        array $data = []
    )
    {
        // Setup class
        $this->_collectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_stockFilter = $stockFilter;
        $this->_parachuteCatalogLayer = $catalogLayer;

        // Pass params to base constructor
        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $catRepo,
            $urlHelper,
            $data
        );
    }

    public function getLayer()
    {
        return $this->_parachuteCatalogLayer;
    }

    /**
     * Configures product collection from a layer and returns its instance.
     *
     * Also in the scope of a product collection configuration, this method initiates configuration of Toolbar.
     * The reason to do this is because we have a bunch of legacy code
     * where Toolbar configures several options of a collection and therefore this block depends on the Toolbar.
     *
     * This dependency leads to a situation where Toolbar sometimes called to configure a product collection,
     * and sometimes not.
     *
     * To unify this behavior and prevent potential bugs this dependency is explicitly called
     * when product collection initialized.
     *
     * @return Collection
     */
    private function initializeProductCollection()
    {
        $layer = $this->getLayer();
        /* @var $layer Layer */
        if ($this->getShowRootCategory()) {
            $this->setCategoryId($this->_storeManager->getStore()->getRootCategoryId());
        }

        // if this is a product view page
        if ($this->_coreRegistry->registry('product')) {
            // get collection of categories this product is associated with
            $categories = $this->_coreRegistry->registry('product')
                ->getCategoryCollection()->setPage(1, 1)
                ->load();
            // if the product is associated with any category
            if ($categories->count()) {
                // show products from this category
                $this->setCategoryId(current($categories->getIterator())->getId());
            }
        }

        $origCategory = null;
        if ($this->getCategoryId()) {
            try {
                $category = $this->categoryRepository->get($this->getCategoryId());
            } catch (NoSuchEntityException $e) {
                $category = null;
            }

            if ($category) {
                $origCategory = $layer->getCurrentCategory();
                $layer->setCurrentCategory($category);
            }
        }

        $now = date('Y-m-d');
        
        // $collection = $this->_collectionFactory->create()
        //     ->addAttributeToSelect('*')
        //     ->addMinimalPrice()
        //     ->addFinalPrice()
        //     ->addTaxPercents()
        //     ->addAttributeToSelect('special_from_date')
        //     ->addAttributeToSelect('special_to_date')
        //     ->addAttributeToFilter('status', Status::STATUS_ENABLED)
        //     ->addAttributeToFilter('special_price', ['neq' => ''])
        //     ->addAttributeToFilter('special_from_date', ['date' => true, 'to' => $now], 'left')
        //     ->addAttributeToFilter(
        //         'special_to_date', 
        //         [
        //             'or' => [
        //                 0 => [
        //                     'date' => true,
        //                     'from' => $now
        //                 ],
        //                 1 => [
        //                     'is' => new Zend_Db_Expr('null')
        //                 ],
        //             ]
        //         ], 
        //         'left')
        //     ->setPageSize(18);

        // $collection = $this->_collectionFactory->create()->addAttributeToFilter('status', Status::STATUS_ENABLED);
        // $this->_stockFilter->addIsInStockFilterToCollection($collection);
        // $layer->prepareProductCollection($collection);
        $collection = $layer->getProductCollection();

        // $collection
        // ->addAttributeToSelect('*')
        // ->addMinimalPrice()
        // ->addFinalPrice()
        // ->addTaxPercents()
        // ->addAttributeToFilter('status', Status::STATUS_ENABLED)
        // ->addAttributeToSelect('special_from_date')
        // ->addAttributeToSelect('special_to_date')
        // ->addAttributeToFilter('special_price', ['neq' => ''])
        // ->addAttributeToFilter('special_from_date', ['date' => true, 'to' => $now], 'left')
        // ->addAttributeToFilter(
        //     'special_to_date', 
        //     [
        //         'or' => [
        //             0 => [
        //                 'date' => true,
        //                 'from' => $now
        //             ],
        //             1 => [
        //                 'is' => new Zend_Db_Expr('null')
        //             ],
        //         ]
        //     ], 
        //     'left');

        // Update the collection in the layer
        //$layer->prepareProductCollection($collection);

        // Get the product collection from the layer - seems to throw the layered nav, toolbar and list out of whack. 
        // Presumably because it overwrites the reference of the collection the rest relies on
        // In any case, we don't need to get the collection again at this point, merely an illustration
        // $collection = $layer->getProductCollection();
        // $collectionTwo = $layer->_getProductCollection(); // would work

        $this->prepareSortableFieldsByCategory($layer->getCurrentCategory());

        if ($origCategory) {
            $layer->setCurrentCategory($origCategory);
        }

        $this->addToolbarBlock($collection);

        $this->_eventManager->dispatch(
            'catalog_block_product_list_collection',
            ['collection' => $collection]
        );

        $this->_productCollection = $collection;

        return $collection;
    }

    /**
     * Add toolbar block from product listing layout
     *
     * @param Collection $collection
     */
    private function addToolbarBlock(Collection $collection)
    {
        $toolbarLayout = $this->getToolbarFromLayout();

        if ($toolbarLayout) {
            $this->configureToolbar($toolbarLayout, $collection);
        }
    }

    /**
     * @inheritdoc
     */
    public function _getProductCollection()
    {
        if ($this->_productCollection === null) {
            $this->_productCollection = $this->initializeProductCollection();
        }

        return $this->_productCollection;
    }

    public function getLoadedProductCollection()
    {
        return $this->_productCollection;
    }

    public function setProductCollection(AbstractCollection $collection)
    {
        $this->_productCollection = $collection;
    }
}