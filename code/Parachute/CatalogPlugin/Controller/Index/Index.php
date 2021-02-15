<?php
/**
 * Parachute
 *
 * @category  Parachute
 * @package   Parachute_CatalogPlugin
 * @copyright Copyright (C) 2019 Parachute (https://www.thisisparachute.com.com)
 */

namespace Parachute\CatalogPlugin\Controller\Index;

use Magento\Framework\Controller\ResultInterface;
use Magento\Catalog\Block\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Module\Dir;
use Magento\Framework\Component\ComponentRegistrar;
use Magento\Framework\Component\ComponentRegistrarInterface;

class Index extends \Magento\Framework\App\Action\Action
{
    // Fields
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $_productRepo;

    /**
     * @var \Magento\Eav\Model\Config
     */
    protected $_eavConfig;

    /**
     * @var \Magento\Framework\Component\ComponentRegistrarInterface
     */
    protected $_componentRegistrar;

    // Constructor
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ProductRepositoryInterface $productRepo,
        \Magento\Eav\Model\Config $eavConfig,
        ComponentRegistrarInterface $componentRegistrar
    )
    {
        $this->_productRepo = $productRepo;
        $this->_eavConfig = $eavConfig;
        $this->_componentRegistrar = $componentRegistrar;
        return parent::__construct($context);
    }

    // Methods
    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $time_pre = microtime(true);
        $file = $this->getAttributeCsvFile('attribute-csv-import.csv');
        $csvData = [];
        $updatedProducts = [];
        $failedProducts = [];
        $result = '';

        // Did we find a file to import?
        if(is_null($file) || $file == false)
        {
            var_dump('No CSV to import');die();
        }

        // Great, let's try and parse each line and update the relevant product
        $loop = 0;

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            if($loop != 0)
            {
                $csvData[] = $data;
                $site = isset($data[0]) ? $data[0] : '';
                $sykesSku = isset($data[1]) ? $data[1] : '';
                $sku = isset($data[2]) ? $data[2] : '';
                $title = isset($data[3]) ? $data[3] : '';
                $stock = isset($data[4]) ? $data[4] : -1;
                $productType = isset($data[5]) ? $data[5] : '';
                $size = isset($data[6]) ? $data[6] : '';
                $format = isset($data[7]) ? $data[7] : '';
                $cover = isset($data[8]) ? $data[8] : '';
                $material = isset($data[9]) ? $data[9] : '';
                $colour = isset($data[10]) ? $data[10] : '';

                // Key=>Val pairs of the attributes we want to set and their values from the CSV row
                $attrArr = [
                    'size' => $size,
                    'format' => $format,
                    'cover' => $cover,
                    'material' => $material,
                    'colour' => $colour
                ];

                try
                {
                    // Get the product, returns a NoSuchEntityException if it doesn't exist
                    $product = $this->_productRepo->get($sku, true);

                    // Make sure a valid product was returned for that sku
                    if(!is_null($product))
                    {
                        // Set the attributes for the filters
                        // if(!empty($productType)) $product->setData('product_type', $productType);

                        if(!empty($attrArr))
                        {
                            foreach($attrArr as $code=>$valueLabel)
                            {
                                if(!empty($code) && !empty($valueLabel))
                                {
                                    $attribute = $this->_eavConfig->getAttribute('catalog_product', $code);
                                    $options = $attribute->getSource()->getAllOptions();
                                    $valueId = $this->parseAttributeValue($options, $valueLabel);

                                    // Set our value if our value id was parsed successfully!
                                    if(!empty($valueId))
                                    {
                                        $product->setCustomAttribute($code, $valueId);
                                        $product->getResource()->saveAttribute($product, $code); // Quicker than saving the whole product
                                    }
                                }
                            }
                        }

                        // Save
                        // $product->save();
                        // $this->_productRepo->save($product);
                        $updatedProducts[] = $product;
                    }
                }
                catch(\Magento\Framework\Exception\NoSuchEntityException $e)
                {
                    $failedProducts[] = $sku;
                }
            }
            
            $loop++;
        }

        fclose($file);

        // Let us know what products were updated
        if(!empty($updatedProducts))
        {
            echo '<h1>Products Updated:</h1>';
            echo '<ul>';
            foreach($updatedProducts as $product)
            {
                echo '<li>' . $product->getSku() . ' - ' .  $product->getName() . '</li>';
            }
            echo '</ul>';
        }

        // Let us know what products could not be found
        if(!empty($failedProducts))
        {
            echo '<h1>Failed Products (Entity did not exist):</h1>';
            echo '<ul>';
            foreach($failedProducts as $product)
            {
                echo '<li>' . $product->getSku() . '</li>';
            }
            echo '</ul>';
        }
        
        $time_post = microtime(true);
        $exec_time = $time_post - $time_pre;
        var_dump('Updated in: ' . $exec_time);
        die();

        return $this->jsonResponse($result);
    }
    
    private function getAttributeCsvFile(string $fileName)
    {
        $moduleDir = $this->_componentRegistrar->getPath(ComponentRegistrar::MODULE, 'Parachute_CatalogPlugin');
        $filePath = $moduleDir . '/import/' . $fileName;
        
        return fopen($filePath, 'r');
    }

    private function parseAttributeValue(array $options, string $valueLabel)
    {
        $valueIdStr = '';

        if(!empty($options))
        {
            foreach($options as $opt) 
            {
                if(isset($opt['label']) && $opt['label'] == $valueLabel)
                {
                    $valueIdStr = $opt['value'];
                    break;
                }
            }
        }

        return $valueIdStr;
    }
}