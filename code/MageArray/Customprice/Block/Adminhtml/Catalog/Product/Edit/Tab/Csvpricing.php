<?php

namespace MageArray\Customprice\Block\Adminhtml\Catalog\Product\Edit\Tab;

use Magento\Backend\Block\Widget\Tab\TabInterface;

/**
 * Class Csvpricing
 * @package MageArray\Customprice\Block\Adminhtml\Catalog\Product\Edit\Tab
 */
class Csvpricing extends \Magento\Backend\Block\Media\Uploader implements TabInterface
{
    /**
     * @var string
     */
    protected $_template = 'MageArray_Customprice::customprice/catalog/product/tab/csv.phtml';

    /**
     * @var \Magento\Backend\Model\UrlFactory
     */
    protected $_urlFactory;

    /**
     * @var
     */
    protected $_config;

    /**
     * @var \Magento\Framework\Json\EncoderInterface
     */
    protected $_jsonEncoder;

    /**
     * @var \MageArray\Customprice\Model\CsvpriceFactory
     */
    protected $_csvpriceFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \MageArray\Customprice\Helper\Data
     */
    protected $_dataHelper;

    /**
     * @var
     */
    protected $_currentProduct;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Csvpricing constructor.
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\File\Size $fileSize
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Backend\Model\UrlFactory $urlFactory
     * @param \MageArray\Customprice\Model\CsvpriceFactory $csvpriceFactory
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \MageArray\Customprice\Helper\Data $dataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\File\Size $fileSize,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\UrlFactory $urlFactory,
        \MageArray\Customprice\Model\CsvpriceFactory $csvpriceFactory,
        \MageArray\Customprice\Model\ResourceModel\Csvprice $csvprice,
        \Magento\Framework\Registry $coreRegistry,
        \MageArray\Customprice\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->_jsonEncoder = $jsonEncoder;
        $this->_urlFactory = $urlFactory;
        $this->_csvpriceFactory = $csvpriceFactory;
        $this->csvprice = $csvprice;
        $this->_coreRegistry = $coreRegistry;
        $this->_dataHelper = $dataHelper;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $fileSize, $data);
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    /**
     * @return mixed
     */
    public function getConfigJson()
    {
        $url = $this->_urlFactory->create()->getUrl(
            'customprice/customcsv/index',
            ['_secure' => true]
        );
        $this->getConfig()->setUrl($url);
        $this->getConfig()->setParams(['form_key' => $this->getFormKey()]);
        return $this->_jsonEncoder->encode($this->getConfig()->getData());
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getConfig()
    {
        if ($this->_config === null) {
            $this->_config = new \Magento\Framework\DataObject();
        }

        return $this->_config;
    }

    /**
     * @return string
     */
    public function getCsvFileData()
    {
        $productId = $this->_coreRegistry->registry('current_product')->getId();

        $csvpricingData = $this->csvprice->addCsvFilter($productId, 0);
        /*
        $csvpricingModel = $this->_csvpriceFactory->create();
        $csvpricingCollection = $csvpricingModel->getCollection()
            ->addFieldToFilter('product_id', $productId)
            ->addFieldToFilter('option_sku', 0);
        $csvpricingCollection->getSelect()->limit(1);
        $csvpricingData = $csvpricingCollection->getData();
        $this->_currentProduct = $csvpricingCollection->getData(); */
        if (!empty($csvpricingData)) {
            $csvprice = $csvpricingData['csv_price'];
            if (!empty($csvprice)) {
                $jsonDecode = json_decode($csvprice, true);
                return $jsonDecode['pricesheet'];
            } else {
                return '';
            }
        } else {
            return '';
        }
        return '';
    }

    /**
     * @return mixed
     */
    public function getColumnLabel()
    {
        $columnLable = $this->getProduct()->getColumnLabels();
        if ($columnLable != "") {
            return $columnLable;
        } else {
            return $this->_dataHelper
                ->getStoreConfig('customprice/general/column_label');
        }
    }

    /**
     * @return mixed
     */
    public function getRowLabel()
    {
        $rowLable = $this->getProduct()->getRowLabels();
        if ($rowLable != "") {
            return $rowLable;
        } else {
            return $this->_dataHelper
                ->getStoreConfig('customprice/general/row_label');
        }
    }

    /**
     * @return mixed
     */
    public function getOptionFieldName()
    {
        return $this->getProduct()->getCsvCsvLabel();
    }

    /**
     * @return string
     */
    public function getProductCustomOption()
    {
        $final = '';
        $id = $this->getProduct()->getId();
        $product = $this->getProduct();
        foreach ($product->getOptions() as $o) {
            if (strtolower($o->getTitle()) == strtolower($this->getOptionFieldName())) {
                $final = $o;
                break;
            }
        }
        return $final;
    }

    /**
     * @param $optId
     * @return string
     */
    public function getCsvPathFile($optId)
    {
        $productId = $this->_coreRegistry->registry('current_product')->getId();

        $data = $this->csvprice->addCsvFilter($productId, $optId);
        // echo "<pre>"; print_R($data);exit;
        /* $csvpricingCollection = $this->_csvpriceFactory->create()->getCollection()
        ->addFieldToFilter('product_id', $productId)
        ->addFieldToFilter('option_sku', $optId)->setPageSize(1);
        $data = $csvpricingCollection->getData(); */
        if (isset($data) && !empty($data)) {
            $fileUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . DIRECTORY_SEPARATOR . 'csvfiles' . $data['file_name'];
            return "<a target='_black' href='" . $fileUrl . "' >Download</a>";
        } else {
            return '';
        }
    }

    /**
     * @param $optId
     * @return string
     */
    public function getCsvFileDataForView($optId)
    {
        $productId = $this->_coreRegistry->registry('current_product')->getId();

        $data = $this->csvprice->addCsvFilter($productId, $optId);
        if (count($data) > 0) {
            $jsonDecode = json_decode($data['csv_price'], true);
            return $jsonDecode['pricesheet'];
        } else {
            return '';
        }
    }

    /**
     * @return mixed
     */
    public function getTabLabel()
    {
        return __('CSV Pricing');
    }

    /**
     * @return mixed
     */
    public function getTabTitle()
    {
        return __('CSV Pricing');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
}
