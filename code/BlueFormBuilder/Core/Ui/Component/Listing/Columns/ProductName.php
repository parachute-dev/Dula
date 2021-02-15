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

namespace BlueFormBuilder\Core\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class ProductName extends Column
{
    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CollectionFactory  $productCollectionFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CollectionFactory $productCollectionFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder            = $urlBuilder;
        $this->productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $productCollection = $this->productCollectionFactory->create();
        $productCollection->addAttributeToSelect(['name']);
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['product_id'])) {
                    $label = $item['product_id'];
                    foreach ($productCollection as $product) {
                        if ($product->getId() == $item['product_id']) {
                            $url = $this->urlBuilder->getUrl(
                                'catalog/product/edit',
                                [
                                    'id' => $product->getId()
                                ]
                            );
                            $label = $product->getName();
                            break;
                        }
                    }
                    $item['product_id'] = $label;
                }
            }
        }
        return $dataSource;
    }
}
