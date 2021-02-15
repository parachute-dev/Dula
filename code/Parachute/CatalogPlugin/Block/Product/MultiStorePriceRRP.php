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

use Magento\Catalog\Block\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

class MultiStorePriceRRP extends \Magento\Catalog\Block\Product\Price 
{
    // Fields
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $_productFactory;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $_productRepo;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    protected $_product;

    /**
     * @var \Magento\Catalog\Model\Product\Type
     */
    protected $_productType;

    /**
     * @var \Magento\Catalog\Model\Product\CatalogPrice
     */
    protected $_catalogPrice;

    /**
     * @var \Magento\Catalog\Model\Product\CatalogPriceFactory
     */
    protected $_catalogPriceFactory;

    /**
     * @var \Magento\Framework\Pricing\Price\Factory
     */
    protected $_priceCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    // Constructor
    public function __construct(
        ProductRepositoryInterface $productRepo,
        Type $productType,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $collectionFactory,
        \Magento\Framework\Pricing\Price\Factory $priceCollectionFactory,
        \Magento\Catalog\Model\Product\CatalogPrice $catalogPrice,
        \Magento\Catalog\Model\Product\CatalogPriceFactory $catalogPriceFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Catalog\Helper\Data $catalogData,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Checkout\Helper\Cart $cartHelper,
        array $data = []
    ) 
    {
        // Pass params to base constructor
        parent::__construct(
            $context,
            $jsonEncoder,
            $catalogData,
            $registry,
            $string,
            $mathRandom,
            $cartHelper,
            $data
        );

        // Setup class
        $this->_productRepo = $productRepo;
        $this->_collectionFactory = $collectionFactory;
        $this->_priceCollectionFactory = $priceCollectionFactory;
        $this->_productFactory = $productFactory;
        $this->_product = $this->getProduct(); 
        $this->_productType = $productType;
        $this->_catalogPrice = $catalogPrice;
        $this->_catalogPriceFactory = $catalogPriceFactory;
        $this->_customerSession = $customerSession;
        $this->_storeManager = $storeManager;
    }

    // Methods
    /**
     * Retrieve the final price for a product with a provided id 
     * and the store with the given store id.
     * 
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFinalPriceForStore(
        int $productId, 
        int $storeId) : float 
    {
        // Output vars
        $price = 0.00;

        // Store and customer data
        $store = $this->_storeManager->getStore($storeId);
        $customerGroupId = $this->_customerSession->isLoggedIn() ? $this->_customerSession->getData('customer_group_id') : 0;

        // Get the product with that entity id
        // Get the price data for the current user's customer group and the requested store id
        // Only get the product if it is enabled
        // Filter the data by the store id
        $collection = $this->_collectionFactory->create()
            ->addAttributeToSelect('*')
            ->addPriceData($customerGroupId, $storeId)
            ->addAttributeToFilter('entity_id', $productId)
            ->addAttributeToFilter('status', Status::STATUS_ENABLED)
            ->addStoreFilter($storeId)
            ->addWebsiteFilter($storeId) // no effect for price index, see below
            ->setPageSize(1)
            ->load();

        // TAKE THIS OUT FOR NOW, NO FALLBACK AS MAY CAUSE CONFUSION
        // We could find no analogous product data for the default website
        // Try and get the data purely for the trade website instead
        // This can happen when a product only exists for one website
        // if(is_null($collection) || is_null($collection->getItems()) || empty($collection->getItems()))
        // {
        //     // Update store var values to be that of the currently viewed store
        //     $store = $this->_storeManager->getStore($storeId);
        //     $storeId = $this->_storeManager->getStore()->getId();

        //     // Get the product from the currently viewed store's catalogue
        //     $collection = $this->_collectionFactory->create()
        //         ->addAttributeToSelect('*')
        //         ->addPriceData($customerGroupId, $storeId)
        //         ->addAttributeToFilter('entity_id', $productId)
        //         ->addAttributeToFilter('status', Status::STATUS_ENABLED)
        //         ->addStoreFilter($storeId)
        //         ->addWebsiteFilter($storeId) // no effect for price index, see below
        //         ->setPageSize(1)
        //         ->load();
        // }

        // We have an issue when dealing with the product collection
        // Whenever the product collection binds the raw data of the query to the object model
        // it is ignoring the store/website filter for pricing. The raw query is correct
        // but something is happening further down the chain that overrides the correct pricing data
        // with that for the currently viewed store.

        // Specifically from the data from the 'catalog_product_index_price' table.
        // Magento tries to "help" by only loading the prices for the currently viewed store, 
        // regardless of any price filtering for a specific store id

        // We get around this by just nabbing the raw data returned for the query
        // Which should have an array full of matching items (in this case, one)
        // that contains keys for pricing data - we then just nab and return 'final_price' below.
        $rawCollectionData = $collection->loadData()->getData();
        $rawCollectionProductData = !empty($rawCollectionData) && isset($rawCollectionData[0]) ? $rawCollectionData[0] : null;

        // In case we need a Product object later on we'll grab it here
        // This will not necessarily have the pricing data for the given $storeId (see: above)
        $product = $collection->getFirstItem();
        $productInstance = !is_null($product) ? $product->getTypeInstance(true) : null;

        // Update product
        $this->_product = $product;

        // #### DEBUG STUFF ####
        // echo '<pre>';
        //var_dump($rawCollectionData); 
        // var_dump($rawCollectionProductData); 
        // var_dump($product->getPriceRrp());
        // var_dump($product->getUnitPrice());
        // die();

        // $priceCollection = $this->_priceCollectionFactory->create(
        //     $product, 
        //     $product->getTypeId() == 'simple' ? '\Magento\Catalog\Pricing\Price\FinalPrice' : get_class($product->getPriceModel()), 
        //     1, 
        //     []);
        

        // Handy var_dumps
        // var_dump($collection->loadData()->getData());
        // $collection->getSelect()->assemble();
        // var_dump($collection->getSelect()->__toString());
        // var_dump($collection->getSize());
        //var_dump(get_class_methods($this->_collectionFactory->create()));
        // var_dump($product->getPrice());
        // var_dump($product->getStoreId());
        // var_dump($product->getName());

        // var_dump(get_class_methods($priceCollection));
        //var_dump(get_class_methods($collection->getSelect()));
        // var_dump(get_class_methods($priceCollection));
        // var_dump($priceCollection->getValue());
        // var_dump(get_class_methods($priceCollection));
        // var_dump($collection->getFirstItem()->getData());
        // var_dump(get_class_methods($this->_productRepo));
        // $product = $this->_productFactory->create()->setStore(1)->load($productId);
        //var_dump(get_class_methods($product));
        //var_dump(get_class_methods($this->_productFactory->create()->setStore(1)));
        //var_dump($product->setStoreId(1));
        // var_dump(get_class_methods($this->_productFactory->create()->setStore(1)->load($productId)));
        // var_dump(get_class_methods($product->getPriceModel()));
        // var_dump($this->_productType->priceFactory($product->getTypeId())->getFinalPrice(1, $product));

        //$catalogPriceModel = $this->_catalogPriceFactory->create(get_class($product->getPriceModel()));
        //$catalogPriceModel->getCatalogPrice($product, $store, $inclTax);
        // var_dump($this->_product->getFinalPrice());die();

        //var_dump($this->_catalogPrice->getCatalogPrice($product, $store, false));
        //foreach($priceCollection as $col) { var_dump($col->getData()); }
        // die();
        // #### END DEBUG STUFF ####

        // Make sure an entity with that sku exists
        if(!is_null($rawCollectionProductData) && !empty($rawCollectionProductData) && isset($rawCollectionProductData['final_price']))
        {
            // Return the price from the raw collection data
            return (float)$rawCollectionProductData['final_price'];

            // Old price factory way of getting things
            // return (float)$this->_productType
            //     ->priceFactory($product->getTypeId())
            //     ->getFinalPrice(1, $product);
        }   

        return $price;
    }

    /**
     * Retrieve the recommended retail price ('price_rrp' attribute value) for a product with a provided id 
     * and the store with the given store id.
     * 
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRRPForStore(
        int $productId, 
        int $storeId) : float 
    {
        // Output vars
        $price = 0.00;

        // Store and customer data
        $store = $this->_storeManager->getStore($storeId);
        $customerGroupId = $this->_customerSession->isLoggedIn() ? $this->_customerSession->getData('customer_group_id') : 0;

        // Get the product with that entity id
        // Get the price data for the current user's customer group and the requested store id
        // Only get the product if it is enabled
        // Filter the data by the store id
        $collection = $this->_collectionFactory->create()
            ->addAttributeToSelect('*')
            ->addPriceData($customerGroupId, $storeId)
            ->addAttributeToFilter('entity_id', $productId)
            ->addAttributeToFilter('status', Status::STATUS_ENABLED)
            ->addStoreFilter($storeId)
            ->addWebsiteFilter($storeId) // no effect for price index, see below
            ->setPageSize(1)
            ->load();

        // In case we need a Product object later on we'll grab it here
        // This will not necessarily have the pricing data for the given $storeId (see: above)
        $product = $collection->getFirstItem();
        $productInstance = !is_null($product) ? $product->getTypeInstance(true) : null;

        // Update product
        $this->_product = $product;

        // #### DEBUG STUFF ####
        // echo '<pre>';
        //var_dump($rawCollectionData); 
        // var_dump($rawCollectionProductData); 
        // var_dump($product->getPriceRrp());
        // var_dump($product->getUnitPrice());
        // die();
        // #### END DEBUG STUFF ####

        // Make sure an entity with that sku exists
        if(!is_null($product) && !is_null($product->getPriceRrp()))
        {
            // Return the price from the raw collection data
            return (float)$product->getPriceRrp();
        }   

        return $price;
    }

    /**
     * Retrieve the unit price ('unit_price' attribute value) for a product with a provided id 
     * and the store with the given store id.
     * 
     * @return float
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUnitPriceForStore(
        int $productId, 
        int $storeId) : float 
    {
        // Output vars
        $price = 0.00;

        // Store and customer data
        $store = $this->_storeManager->getStore($storeId);
        $customerGroupId = $this->_customerSession->isLoggedIn() ? $this->_customerSession->getData('customer_group_id') : 0;

        // Get the product with that entity id
        // Get the price data for the current user's customer group and the requested store id
        // Only get the product if it is enabled
        // Filter the data by the store id
        $collection = $this->_collectionFactory->create()
            ->addAttributeToSelect('*')
            ->addPriceData($customerGroupId, $storeId)
            ->addAttributeToFilter('entity_id', $productId)
            ->addAttributeToFilter('status', Status::STATUS_ENABLED)
            ->addStoreFilter($storeId)
            ->addWebsiteFilter($storeId) // no effect for price index, see below
            ->setPageSize(1)
            ->load();

        // In case we need a Product object later on we'll grab it here
        // This will not necessarily have the pricing data for the given $storeId (see: above)
        $product = $collection->getFirstItem();
        $productInstance = !is_null($product) ? $product->getTypeInstance(true) : null;

        // Update product
        $this->_product = $product;

        // #### DEBUG STUFF ####
        // echo '<pre>';
        //var_dump($rawCollectionData); 
        // var_dump($rawCollectionProductData); 
        // var_dump($product->getPriceRrp());
        // var_dump($product->getUnitPrice());
        // die();
        // #### END DEBUG STUFF ####

        // Make sure an entity with that sku exists
        if(!is_null($product) && !is_null($product->getUnitPrice()))
        {
            // Return the price from the raw collection data
            return (float)$product->getUnitPrice();
        }   

        return $price;
    }

    /**
     * Retrieve the block's instance of the Store Manager.
     * 
     * @return \Magento\Store\Model\StoreManagerInterface
     */
    public function getStoreManager() {
        return $this->_storeManager;
    }

    /**
     * Retrieve the block's instance of the Customer Session.
     * 
     * @return \Magento\Customer\Model\Session
     */
    public function getCustomerSession() {
        return $this->_customerSession;
    }
}