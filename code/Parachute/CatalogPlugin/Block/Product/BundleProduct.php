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
use Magento\Catalog\Model\Layer;
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
use Magento\Framework\Registry;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\CatalogInventory\Helper\Stock;
use Magento\Framework\Url\EncoderInterface;
use Magento\Checkout\Helper\Cart;

/**
 * Class BundleProduct
 * @package Parachute\CatalogPlugin\Block\BundleProduct
 */
class BundleProduct extends \Magento\Framework\View\Element\Template
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
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;
    
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $_productCollection;

    /**
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    protected $_stockFilter;

    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $_urlEncoder;

    /**
     * @var \Magento\Framework\App\Rss\UrlBuilderInterface
     */
    protected $_urlBuilder;

    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $_formKey;

    /**
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $_cartHelper;

    /**
     * @param \Magento\Framework\Url\EncoderInterface $urlEncoder
     * @param \Magento\Framework\App\Rss\UrlBuilderInterface $urlBuilder
     * @param \Magento\Framework\Data\Form\FormKey $formKey
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param CollectionFactory $productCollectionFactory
     * @param Visibility $catalogProductVisibility
     * @param Context $context
     * @param PostHelper $postDataHelper
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @param Magento\Framework\Registry $registry
     * @param Data $urlHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\App\Rss\UrlBuilderInterface $urlBuilder,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Checkout\Helper\Cart $cartHelper,
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        Context $context,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $catRepo,
        Stock $stockFilter,
        Data $urlHelper,
        Registry $registry,
        array $data = []
    )
    {
        // Setup class
        $this->_urlEncoder = $urlEncoder;
        $this->_urlBuilder = $urlBuilder;
        $this->_formKey = $formKey;
        $this->_cartHelper = $cartHelper;
        $this->_collectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_stockFilter = $stockFilter;
        $this->_registry = $registry;
        $this->_product = $this->getProduct();

        // Pass params to base constructor
        parent::__construct(
            $context,
            $data
        );
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
        // Container for the bundle's containing the currently viewed product
        $matchingBundleProductIds = [];
        $matchingBundleProductCollection = $this->_collectionFactory->create();

        // Make sure we've got a product in our current view context
        if(is_null($this->_product))
            return $matchingBundleProductCollection;

        // Current view's product id
        $pid = $this->_product->getId();

        // Get all bundle products
        $collection = $this->_collectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToSelect('type_id')
            ->addAttributeToFilter('type_id', ['eq' => 'bundle'])
            ->addAttributeToFilter('status', Status::STATUS_ENABLED);
            
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInSiteIds());

        // Only get products in stock
        $this->_stockFilter->addInStockFilterToCollection($collection);

        // Make sure we've got bundle products
        if($collection != null && $collection->count() > 0)
        {
            foreach($collection as $p) 
            {
                // Get the bundle product instance
                $bundleProduct = $p->getTypeInstance(true);
                
                // Get bundle options with an 'entity_id' that matches the current view's product id
                $bundleProductSelectionProdsCollection = 
                    $p->getTypeInstance(true)
                    ->getSelectionsCollection($p->getTypeInstance(true)->getOptionsIds($p), $p)
                    ->addAttributeToFilter('entity_id', ['eq' => $pid]);

                // If we have matching options then add this to our matching bundle's array
                if($bundleProductSelectionProdsCollection != null && 
                count($bundleProductSelectionProdsCollection) > 0 &&
                !in_array($p->getId(), $matchingBundleProductIds))
                {
                    $matchingBundleProductIds[] = $p->getId();
                }
            }
        }

        // Load a collection with our matching bundle product ids
        if($matchingBundleProductIds != null && count($matchingBundleProductIds) > 0)
        {
            $matchingBundleProductCollection
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $matchingBundleProductIds]);
        }
        else
        {
            return null;
        }

        return $matchingBundleProductCollection;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        if (is_null($this->_product)) {
            $this->_product = $this->_registry->registry('product');

            if (!$this->_product->getId()) {
                throw new LocalizedException(__('Failed to initialize product'));
            }
        }

        return $this->_product;
    }

    /**
     * @inheritdoc
     */
    public function getProductCollection()
    {
        if ($this->_productCollection === null) {
            $this->_productCollection = $this->initializeProductCollection();
        }

        return $this->_productCollection;
    }

    /**
     * Retrieve url for direct adding product to cart
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $additional
     * @return string
     */
    public function getAddToCartUrl($product, $additional = [])
    {
        // if ($this->hasCustomAddToCartUrl()) {
        //     return $this->getCustomAddToCartUrl();
        // }

        // if ($this->getRequest()->getParam('wishlist_next')) {
        //     $additional['wishlist_next'] = 1;
        // }

        $addUrlKey = \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED;
        $addUrlValue = $this->_urlBuilder->getUrl('*/*/*', ['_use_rewrite' => true, '_current' => true]);
        $additional[$addUrlKey] = $this->_urlEncoder->encode($addUrlValue);

        return $this->_cartHelper->getAddUrl($product, $additional);
    }

    /**
     * get form key
     *
     * @return string
     */
    public function getFormKey()
    {
         return $this->_formKey->getFormKey();
    }
}