<?php
namespace Parachute\CatalogPlugin\Model;

use Zend_Db_Expr;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Block\Product;
use Magento\Catalog\Block\Product\ProductList\Toolbar;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Config;
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

class Layer extends \Magento\Catalog\Model\Layer
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

    //Apart from the default construct argument you need to add your model from which your product collection is fetched.

    public function __construct(
        CollectionFactory $productCollectionFactory,
        Visibility $catalogProductVisibility,
        PostHelper $postDataHelper,
        Resolver $layerResolver,
        CategoryRepositoryInterface $catRepo,
        Stock $stockFilter,
        \Magento\Catalog\Model\Layer\ContextInterface $context,
        \Magento\Catalog\Model\Layer\StateFactory $layerStateFactory,
        AttributeCollectionFactory $attributeCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Product $catalogProduct,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        CategoryRepositoryInterface $categoryRepository,
        array $data = []
    ) 
    {
        // Setup class
        $this->_collectionFactory = $productCollectionFactory;
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_stockFilter = $stockFilter;

        parent::__construct(
            $context,
            $layerStateFactory,
            $attributeCollectionFactory,
            $catalogProduct,
            $storeManager,
            $registry,
            $categoryRepository,
            $data
        );
    }

    public function getProductCollection()
    {

        /*
        Unique id is needed so that when product is loaded /filtered in the custom listing page it will be set in the
         $this->_productCollections array with unique key else you will not get the updated or proper collection.
        */
        $collection = null;

        if (isset($this->_productCollections['parachute_catalogplugin_on_sale'])) {
            $collection = $this->_productCollections['parachute_catalogplugin_on_sale'];
        } else {
            //$collection = Your logic to get your custom collection.
            $now = date('Y-m-d');

            $collection = $this->_collectionFactory->create()
                ->addAttributeToSelect('*')
                ->addMinimalPrice()
                ->addFinalPrice()
                ->addTaxPercents()
                ->addAttributeToSelect('special_from_date')
                ->addAttributeToSelect('special_to_date')
                ->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
                ->addAttributeToFilter('special_price', ['neq' => ''])
                ->addAttributeToFilter('special_from_date', ['date' => true, 'to' => $now], 'left')
                ->addAttributeToFilter(
                    'special_to_date', 
                    [
                        'or' => [
                            0 => [
                                'date' => true,
                                'from' => $now
                            ],
                            1 => [
                                'is' => new Zend_Db_Expr('null')
                            ],
                        ]
                    ], 
                    'left');

            //$this->_stockFilter->addIsInStockFilterToCollection($collection);

            $this->prepareProductCollection($collection);
            $this->_productCollections['parachute_catalogplugin_on_sale'] = $collection;
        }

        return $collection;
    }

    /**
     * Initialize product collection
     *
     * @param \Magento\Catalog\Model\ResourceModel\Product\Collection $collection
     * @return \Magento\Catalog\Model\Layer
     */
    public function prepareProductCollection($collection)
    {
        $this->collectionFilter->filter($collection, $this->getCurrentCategory());

        return $this;
    }
}