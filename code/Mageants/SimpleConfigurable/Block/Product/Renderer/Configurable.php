<?php
/**
 * @category Mageants SimpleConfigurable
 * @package Mageants_SimpleConfigurable
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\SimpleConfigurable\Block\Product\Renderer;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product as CatalogProduct;
use Magento\ConfigurableProduct\Helper\Data;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Store\Model\ScopeInterface;
use Magento\Swatches\Helper\Data as SwatchData;
use Magento\Swatches\Helper\Media;
use Magento\Swatches\Model\Swatch;
use Magento\Framework\App\ObjectManager;
use Magento\Swatches\Model\SwatchAttributesProvider;

/**
 * Swatch renderer block
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Configurable extends \Magento\Swatches\Block\Product\Renderer\Configurable
{
    /**
     * Path to template file with Swatch renderer.
     */
    const SWATCH_RENDERER_TEMPLATE = 'Mageants_SimpleConfigurable::product/view/renderer.phtml';

    /**
     * Path to default template file with standard Configurable renderer.
     */
    const CONFIGURABLE_RENDERER_TEMPLATE = 'Mageants_SimpleConfigurable::product/view/type/options/configurable.phtml';

    /**
     * Action name for ajax request
     */
    const MEDIA_CALLBACK_ACTION = 'swatches/ajax/media';

    /**
     * @var Product
     */
    public $product;

    /**
     * @var SwatchData
     */
    public $swatchHelper;

    /**
     * @var Media
     */
    public $swatchMediaHelper;

    /**
     * Indicate if product has one or more Swatch attributes
     *
     * @deprecated unused
     *
     * @var boolean
     */
    public $isProductHasSwatchAttribute;

    /**
     * @var SwatchAttributesProvider
     */
    private $swatchAttributesProvider;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    private $layoutFactory;

    /**
     * @var \Magento\Catalog\Helper\Output
     */
    private $output;

    private $productobj;

    /**
     * @param Context $context
     * @param ArrayUtils $arrayUtils
     * @param EncoderInterface $jsonEncoder
     * @param Data $helper
     * @param CatalogProduct $catalogProduct
     * @param CurrentCustomer $currentCustomer
     * @param PriceCurrencyInterface $priceCurrency
     * @param ConfigurableAttributeData $configurableAttributeData
     * @param SwatchData $swatchHelper
     * @param Media $swatchMediaHelper
     * @param array $data other data
     * @param SwatchAttributesProvider $swatchAttributesProvider
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        Data $helper,
        CatalogProduct $catalogProduct,
        CurrentCustomer $currentCustomer,
        PriceCurrencyInterface $priceCurrency,
        ConfigurableAttributeData $configurableAttributeData,
        SwatchData $swatchHelper,
        Media $swatchMediaHelper,
        SwatchAttributesProvider $swatchAttributesProvider = null,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Catalog\Helper\Output $output,
        \Magento\Catalog\Model\Product $productobj,
        array $data = []
    ) {
        $this->swatchHelper = $swatchHelper;
        $this->swatchMediaHelper = $swatchMediaHelper;
        $this->swatchAttributesProvider = $swatchAttributesProvider
            ?: ObjectManager::getInstance()->get(SwatchAttributesProvider::class);
        $this->layoutFactory = $layoutFactory;
        $this->output = $output;
        $this->scopeConfig = $context->getScopeConfig();
        $this->productobj = $productobj;
        parent::__construct(
            $context,
            $arrayUtils,
            $jsonEncoder,
            $helper,
            $catalogProduct,
            $currentCustomer,
            $priceCurrency,
            $configurableAttributeData,
            $swatchHelper,
            $swatchMediaHelper,
            $data,
            $swatchAttributesProvider
        );
    }

    /**
     * Get Key for caching block content
     *
     * @return string
     */
    public function getCacheKey()
    {
        return parent::getCacheKey() . '-' . $this->getProduct()->getId();
    }

    /**
     * Get block cache life time
     *
     * @return int
     */
    public function getCacheLifetime()
    {
        return parent::hasCacheLifetime() ? parent::getCacheLifetime() : 3600;
    }

    /**
     * Get Swatch config data
     *
     * @return string
     */
    public function getJsonSwatchConfig()
    {
        $attributesData = $this->getSwatchAttributesData();
        $allOptionIds = $this->getConfigurableOptionsIds($attributesData);
        $swatchesData = $this->swatchHelper->getSwatchesByOptionsId($allOptionIds);

        $config = [];
        foreach ($attributesData as $attributeId => $attributeDataArray) {
            if (isset($attributeDataArray['options'])) {
                $config[$attributeId] = $this->addSwatchDataForAttribute(
                    $attributeDataArray['options'],
                    $swatchesData,
                    $attributeDataArray
                );
            }
        }

        return $this->jsonEncoder->encode($config);
    }

    /**
     * Get number of swatches from config to show on product listing.
     * Other swatches can be shown after click button 'Show more'
     *
     * @return string
     */
    public function getNumberSwatchesPerProduct()
    {
        return $this->_scopeConfig->getValue(
            'catalog/frontend/swatches_per_product',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Set product to block
     *
     * @param Product $product
     * @return $this
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * Override parent function
     *
     * @return Product
     */
    public function getProduct()
    {
        if (!$this->product) {
            $this->product = parent::getProduct();
        }

        return $this->product;
    }

    /**
     * @return array
     */
    public function getSwatchAttributesData()
    {
        return $this->swatchHelper->getSwatchAttributesAsArray($this->getProduct());
    }

    /**
     * @deprecated unused
     * @see isProductHasSwatchAttribute().
     *
     * @codeCoverageIgnore
     * @return void
     */
    public function initIsProductHasSwatchAttribute()
    {
        $this->isProductHasSwatchAttribute = $this->swatchHelper->isProductHasSwatch($this->getProduct());
    }

    /**
     * @codeCoverageIgnore
     * @return bool
     */
    public function isProductHasSwatchAttribute()
    {
        $swatchAttributes = $this->swatchAttributesProvider->provide($this->getProduct());
        return count($swatchAttributes) > 0;
    }

    /**
     * Add Swatch Data for attribute
     *
     * @param array $options
     * @param array $swatchesCollectionArray
     * @param array $attributeDataArray
     * @return array
     */
    public function addSwatchDataForAttribute(
        array $options,
        array $swatchesCollectionArray,
        array $attributeDataArray
    ) {
        $result = [];
        foreach ($options as $optionId => $label) {
            if (isset($swatchesCollectionArray[$optionId])) {
                $result[$optionId] = $this->extractNecessarySwatchData($swatchesCollectionArray[$optionId]);
                $result[$optionId] = $this->addAdditionalMediaData($result[$optionId], $optionId, $attributeDataArray);
                $result[$optionId]['label'] = $label;
            }
        }

        return $result;
    }

    /**
     * Add media from variation
     *
     * @param array $swatch
     * @param integer $optionId
     * @param array $attributeDataArray
     * @return array
     */
    public function addAdditionalMediaData(array $swatch, $optionId, array $attributeDataArray)
    {
        if (isset($attributeDataArray['use_product_image_for_swatch'])
            && $attributeDataArray['use_product_image_for_swatch']
        ) {
            $variationMedia = $this->getVariationMedia($attributeDataArray['attribute_code'], $optionId);
            if (! empty($variationMedia)) {
                $swatch['type'] = Swatch::SWATCH_TYPE_VISUAL_IMAGE;
                $swatch = array_merge($swatch, $variationMedia);
            }
        }

        return $swatch;
    }

    /**
     * Retrieve Swatch data for config
     *
     * @param array $swatchDataArray
     * @return array
     */
    public function extractNecessarySwatchData(array $swatchDataArray)
    {
        $result['type'] = $swatchDataArray['type'];

        if ($result['type'] == Swatch::SWATCH_TYPE_VISUAL_IMAGE && !empty($swatchDataArray['value'])) {
            $result['value'] = $this->swatchMediaHelper->getSwatchAttributeImage(
                Swatch::SWATCH_IMAGE_NAME,
                $swatchDataArray['value']
            );
            $result['thumb'] = $this->swatchMediaHelper->getSwatchAttributeImage(
                Swatch::SWATCH_THUMBNAIL_NAME,
                $swatchDataArray['value']
            );
        } else {
            $result['value'] = $swatchDataArray['value'];
        }

        return $result;
    }

    /**
     * Generate Product Media array
     *
     * @param string $attributeCode
     * @param integer $optionId
     * @return array
     */
    public function getVariationMedia($attributeCode, $optionId)
    {
        $variationProduct = $this->swatchHelper->loadFirstVariationWithSwatchImage(
            $this->getProduct(),
            [$attributeCode => $optionId]
        );

        if (!$variationProduct) {
            $variationProduct = $this->swatchHelper->loadFirstVariationWithImage(
                $this->getProduct(),
                [$attributeCode => $optionId]
            );
        }

        $variationMediaArray = [];
        if ($variationProduct) {
            $variationMediaArray = [
                'value' => $this->getSwatchProductImage($variationProduct, Swatch::SWATCH_IMAGE_NAME),
                'thumb' => $this->getSwatchProductImage($variationProduct, Swatch::SWATCH_THUMBNAIL_NAME),
            ];
        }

        return $variationMediaArray;
    }

    /**
     * @param Product $childProduct
     * @param string $imageType
     * @return string
     */
    public function getSwatchProductImage(Product $childProduct, $imageType)
    {
        if ($this->isProductHasImage($childProduct, Swatch::SWATCH_IMAGE_NAME)) {
            $swatchImageId = $imageType;
            $imageAttributes = ['type' => Swatch::SWATCH_IMAGE_NAME];
        } elseif ($this->isProductHasImage($childProduct, 'image')) {
            $swatchImageId = $imageType == Swatch::SWATCH_IMAGE_NAME ? 'swatch_image_base' : 'swatch_thumb_base';
            $imageAttributes = ['type' => 'image'];
        }

        if (isset($swatchImageId)) {
            return $this->_imageHelper->init($childProduct, $swatchImageId, $imageAttributes)->getUrl();
        }
    }

    /**
     * @param Product $product
     * @param string $imageType
     * @return bool
     */
    public function isProductHasImage(Product $product, $imageType)
    {
        return $product->getData($imageType) !== null && $product->getData($imageType) != SwatchData::EMPTY_IMAGE_VALUE;
    }

    /**
     * @param array $attributeData
     * @return array
     */
    public function getConfigurableOptionsIds(array $attributeData)
    {
        $ids = [];
        foreach ($this->getAllowProducts() as $product) {
            /** @var \Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute $attribute */
            foreach ($this->helper->getAllowAttributes($this->getProduct()) as $attribute) {
                $productAttribute = $attribute->getProductAttribute();
                $productAttributeId = $productAttribute->getId();
                if (isset($attributeData[$productAttributeId])) {
                    $ids[$product->getData($productAttribute->getAttributeCode())] = 1;
                }
            }
        }

        return array_keys($ids);
    }

    /**
     * Produce and return block's html output.
     *
     * @return string
     */
    public function _toHtml()
    {
        $this->setTemplate(
            $this->getRendererTemplate()
        );
        return parent::_toHtml();
    }

    /**
     * @codeCoverageIgnore
     * @return string
     */
    public function getRendererTemplate()
    {
        return $this->isProductHasSwatchAttribute() ?
            self::SWATCH_RENDERER_TEMPLATE : self::CONFIGURABLE_RENDERER_TEMPLATE;
    }

    /**
     * @return string
     */
    public function getMediaCallback()
    {
        return $this->getUrl(self::MEDIA_CALLBACK_ACTION, ['_secure' => $this->getRequest()->isSecure()]);
    }

    /**
     * Return unique ID(s) for each object in system
     *
     * @return string[]
     */
    public function getIdentities()
    {
        if ($this->product instanceof \Magento\Framework\DataObject\IdentityInterface) {
            return $this->product->getIdentities();
        } else {
            return [];
        }
    }
    
    public function isEnable()
    {
        return $this->scopeConfig->getValue(
            'SimpleConfigurable_config/SimpleConfigurable_settings/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getcustomAttributes()
    {
        $reloadAttributes = $this->scopeConfig->getValue(
            'SimpleConfigurable_config/SimpleConfigurable_reload/content',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $reloadAttributes = explode(',', $reloadAttributes);
        $_children = $this->getProduct()->getTypeInstance()->getUsedProducts($this->getProduct());
        $getChildData = [];
        foreach ($_children as $child) {
            $getChildData[$child->getId()] = $this->_getProductInfo($child, $reloadAttributes);
        }

        return $this->jsonEncoder->encode($getChildData);
    }

    /**
     * @param $product
     * @param $reloadValues
     * @param $block
     * @return array
     */
    public function _getProductInfo($product, $reloadValues)
    {
        $reloadMeta = $this->scopeConfig->getValue(
            'SimpleConfigurable_config/SimpleConfigurable_settings/updatemeta',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        if ($reloadMeta) {
            array_push($reloadValues, "meta_keyword", "meta_description");
            $metaSelector = ['meta_keyword'=>"meta[name='keywords']", 'meta_description'=>"meta[name='description']"];
        }

        $productInfo = [];

        $layout = $this->layoutFactory->create();
        foreach ($reloadValues as $reloadValue) {
            $selector = $this->scopeConfig->getValue(
                'SimpleConfigurable_config/SimpleConfigurable_reload/'.$reloadValue,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );

            if (!$selector) {
                if (strpos($reloadValue, 'meta_') !== false) {
                    $selector = $metaSelector[$reloadValue];
                } else {
                    continue;
                }
            }

            if ($reloadValue == 'attributes') {
                $block = $layout->createBlock(
                    'Magento\Catalog\Block\Product\View\Attributes',
                    'product.attributes',
                    [ 'data' => [] ]
                )->setTemplate('product/view/attributes.phtml');

                $currentProduct = $this->_coreRegistry->registry('product');
                $this->_coreRegistry->unregister('product');
                $this->_coreRegistry->register('product', $product);
                $value = $block->setProduct($product)->toHtml();
                $this->_coreRegistry->unregister('product');
                $this->_coreRegistry->register('product', $currentProduct);
            } else {
                $product = $this->productobj->load($product->getId());
                $value = $this->output->productAttribute($product, $product->getData($reloadValue), $reloadValue);
            }

            if ($value) {
                $productInfo[$reloadValue] = [
                    'class'  => $selector,
                    'value'     => $value
                ];
            }
        }

        return $productInfo;
    }

    public function getPreselectOptionId()
    {
        $_children = $this->getProduct()->getTypeInstance()->getUsedProductIds($this->getProduct());
        $optionDatas = $this->getProduct()->getTypeInstance()->getConfigurableOptions($this->getProduct());

        if ($this->getPreselectSource()==0) {
            if (!empty($optionDatas)) :
                foreach ($optionDatas as $opt => $optionData) :
                    foreach ($optionData as $optionVal) :
                        $optionCode[$opt] = $optionVal['attribute_code'];
                        $preoptionCode[$optionVal['attribute_code']] = $optionVal['value_index'];
                        break;
                    endforeach;
                endforeach;
            endif;
            return $this->jsonEncoder->encode($preoptionCode);
        } elseif ($this->getPreselectSource()==1) {
            if (!$this->getProduct()->getIsDefaultSelected()) {
                return;
            } else {
                foreach ($optionDatas as $opt => $optionData) :
                    foreach ($optionData as $optionVal) :
                        $optionCode[$opt] = $optionVal['attribute_code'];
                        break;
                    endforeach;
                endforeach;

                $selectedOpt = [];
                $product = $this->productobj->load($this->getProduct()->getIsDefaultSelected());
                foreach ($optionCode as $k => $optCode) {
                    $selectedOpt[$optCode] = $product->getData($optCode);
                }

                return $this->jsonEncoder->encode($selectedOpt);
            }
        } elseif ($this->getPreselectSource()==2) {
            if (!empty($optionDatas)) :
                foreach ($optionDatas as $opt => $optionData) :
                    foreach ($optionData as $optionVal) :
                        $optionCode[$opt] = $optionVal['attribute_code'];
                        $preoptionCode[$optionVal['attribute_code']] = $optionVal['value_index'];
                        break;
                    endforeach;
                endforeach;
            endif;

            $maxprice = 0;
            $maxpriceproductid = 0;
            $productpricearray = [];
            
            foreach ($_children as $child) {
                $assoproduct = $this->productobj->load($child);
                if ($maxprice < $assoproduct->getPrice()) {
                    $maxprice = $assoproduct->getPrice();
                    $maxpriceproductid = $assoproduct->getId();
                }

                $productpricearray[] = $assoproduct->getPrice();
            }

            if (!empty($productpricearray) && count(array_unique($productpricearray)) === 1) {
                return $this->jsonEncoder->encode($preoptionCode);
            } else {
                $selectedOpt = [];
                $product = $this->productobj->load($maxpriceproductid);
                
                foreach ($optionCode as $k => $optCode) {
                    $selectedOpt[$optCode] = $product->getData($optCode);
                }

                return $this->jsonEncoder->encode($selectedOpt);
            }
        } elseif ($this->getPreselectSource()==3) {
            if (!empty($optionDatas)) :
                foreach ($optionDatas as $opt => $optionData) :
                    foreach ($optionData as $optionVal) :
                        $optionCode[$opt] = $optionVal['attribute_code'];
                        $preoptionCode[$optionVal['attribute_code']] = $optionVal['value_index'];
                        break;
                    endforeach;
                endforeach;
            endif;

            $lowprice = "";
            $lowpriceproductid = 0;
            $productpricearray = [];

            foreach ($_children as $child) {
                $assoproduct = $this->productobj->load($child);
                if ($lowprice=="") {
                    $lowprice = $assoproduct->getPrice();
                    $lowpriceproductid = $assoproduct->getId();
                } else {
                    if ($lowprice > $assoproduct->getPrice()) {
                        $lowprice = $assoproduct->getPrice();
                        $lowpriceproductid = $assoproduct->getId();
                    }
                }

                $productpricearray[] = $assoproduct->getPrice();
            }

            if (!empty($productpricearray) && count(array_unique($productpricearray)) === 1) {
                return $this->jsonEncoder->encode($preoptionCode);
            } else {
                $selectedOpt = [];
                $product = $this->productobj->load($lowpriceproductid);
                
                foreach ($optionCode as $k => $optCode) {
                    $selectedOpt[$optCode] = $product->getData($optCode);
                }

                return $this->jsonEncoder->encode($selectedOpt);
            }
        } else {
            return;
        }
    }

    public function getProductUrls()
    {
        $productUrls = [];
        $_configChild = $this->getProduct()->getTypeInstance()->getUsedProductIds($this->getProduct());
        foreach ($_configChild as $child) {
            $productUrls[$child] = $this->getBaseUrl().$this->productobj->load($child)->getUrlKey();
        }

        return $this->jsonEncoder->encode($productUrls);
    }

    public function replaceUrl()
    {
        return $this->scopeConfig->getValue(
            'SimpleConfigurable_config/SimpleConfigurable_settings/updatepageurl',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getPreselectSource()
    {
        
        $preselectVal = $this->scopeConfig->getValue(
            'SimpleConfigurable_config/SimpleConfigurable_settings/preselect',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if (!$preselectVal) {
            $preselectVal = 0;
        }

        return $preselectVal;
    }
    
    public function getConfigurablePreselectOption()
    {
        $_children = $this->getProduct()->getTypeInstance()->getUsedProducts($this->getProduct());
        $optionDatas = $this->getProduct()->getTypeInstance()->getConfigurableOptions($this->getProduct());

        $optionCode = [];
        $preoptionCode = [];
        if ($this->getPreselectSource()==0) {
            return;
        } elseif ($this->getPreselectSource()==1) {
            if (!$this->getProduct()->getIsDefaultSelected()) {
                return;
            } else {
                foreach ($optionDatas as $opt => $optionData) :
                    foreach ($optionData as $optionVal) :
                        $optionCode[$opt] = $optionVal['attribute_code'];
                        break;
                    endforeach;
                endforeach;

                $selectedOpt = [];
                $product = $this->productobj->load($this->getProduct()->getIsDefaultSelected());
                foreach ($optionCode as $k => $optCode) {
                    $selectedOpt[$k] = $product->getData($optCode);
                }

                return $this->jsonEncoder->encode($selectedOpt);
            }
        } elseif ($this->getPreselectSource()==2) {
            if (!empty($optionDatas)) :
                foreach ($optionDatas as $opt => $optionData) :
                    foreach ($optionData as $optionVal) :
                        $optionCode[$opt] = $optionVal['attribute_code'];
                        $preoptionCode[$optionVal['attribute_code']] = $optionVal['value_index'];
                        break;
                    endforeach;
                endforeach;
            endif;

            $maxprice = 0;
            $maxpriceproductid = 0;
            $productpricearray = [];
            
            foreach ($_children as $child) {
                $assoproduct = $this->productobj->load($child->getId());
                if ($maxprice < $assoproduct->getPrice()) {
                    $maxprice = $assoproduct->getPrice();
                    $maxpriceproductid = $assoproduct->getId();
                }

                $productpricearray[] = $assoproduct->getPrice();
            }

            if (!empty($productpricearray) && count(array_unique($productpricearray)) === 1) {
                return $this->jsonEncoder->encode($preoptionCode);
            } else {
                $selectedOpt = [];
                $product = $this->productobj->load($maxpriceproductid);
                
                foreach ($optionCode as $k => $optCode) {
                    $selectedOpt[$k] = $product->getData($optCode);
                }

                return $this->jsonEncoder->encode($selectedOpt);
            }
        } elseif ($this->getPreselectSource()==3) {
            if (!empty($optionDatas)) :
                foreach ($optionDatas as $opt => $optionData) :
                    foreach ($optionData as $optionVal) :
                        $optionCode[$opt] = $optionVal['attribute_code'];
                        $preoptionCode[$optionVal['attribute_code']] = $optionVal['value_index'];
                        break;
                    endforeach;
                endforeach;
            endif;

            $lowprice = "";
            $lowpriceproductid = 0;
            $productpricearray = [];

            foreach ($_children as $child) {
                $assoproduct = $this->productobj->load($child->getId());
                if ($lowprice=="") {
                    $lowprice = $assoproduct->getPrice();
                    $lowpriceproductid = $assoproduct->getId();
                } else {
                    if ($lowprice > $assoproduct->getPrice()) {
                        $lowprice = $assoproduct->getPrice();
                        $lowpriceproductid = $assoproduct->getId();
                    }
                }

                $productpricearray[] = $assoproduct->getPrice();
            }

            if (!empty($productpricearray) && count(array_unique($productpricearray)) === 1) {
                return $this->jsonEncoder->encode($preoptionCode);
            } else {
                $selectedOpt = [];
                $product = $this->productobj->load($lowpriceproductid);
                
                foreach ($optionCode as $k => $optCode) {
                    $selectedOpt[$k] = $product->getData($optCode);
                }

                return $this->jsonEncoder->encode($selectedOpt);
            }
        } else {
            return;
        }
    }
}
