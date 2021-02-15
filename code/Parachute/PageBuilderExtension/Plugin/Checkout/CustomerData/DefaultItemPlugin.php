<?php

namespace Parachute\PageBuilderExtension\Checkout\Plugin\Checkout\CustomerData;

class DefaultItemPlugin
{
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function afterGetItemData(\Magento\Checkout\CustomerData\DefaultItem $subject, $result)
    {
        $product = $this->productRepository->get($result['product_sku']);

        return \array_merge(
            ['product_sub_name' => $product->getData('product_sub_name')],
            $result
        );
    }
}