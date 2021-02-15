<?php
/**
 * Content
 *
 * @copyright Copyright © 2019 Blackbird. All rights reserved.
 * @author    etienne (Blackbird Team)
 */
declare(strict_types=1);

namespace Blackbird\ContentManager\Model\Sitemap\ItemProvider;

use Blackbird\ContentManager\Api\Data\ContentInterface;
use Magento\Sitemap\Model\ItemProvider\ItemProviderInterface;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;
use Blackbird\ContentManager\Model\Config\Source\ContentType\Visibility;
use Blackbird\ContentManager\Model\Content as ModelContent;
use Blackbird\ContentManager\Model\ContentType;
use Blackbird\ContentManager\Model\ResourceModel\ContentType\CollectionFactory as ContentTypeCollectionFactory;
use Blackbird\ContentManager\Model\ResourceModel\Content\CollectionFactory as ContentCollectionFactory;

class Content implements ItemProviderInterface
{

    /**
     * @var \Blackbird\ContentManager\Model\ResourceModel\ContentType\CollectionFactory
     */
    private $contentTypeCollectionFactory;

    /**
     * @var \Blackbird\ContentManager\Model\ResourceModel\Content\CollectionFactory
     */
    private $contentCollectionFactory;

    /**
     * @var \Magento\Sitemap\Model\SitemapItemInterfaceFactory
     */
    private $itemFactory;

    /**
     * Content constructor.
     *
     * @param \Blackbird\ContentManager\Model\ResourceModel\ContentType\CollectionFactory $contentTypeCollectionFactory
     * @param \Blackbird\ContentManager\Model\ResourceModel\Content\CollectionFactory $contentCollectionFactory
     * @param \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory
     */
    public function __construct(
        ContentTypeCollectionFactory $contentTypeCollectionFactory,
        ContentCollectionFactory $contentCollectionFactory,
        SitemapItemInterfaceFactory $itemFactory
    )
    {
        $this->contentTypeCollectionFactory = $contentTypeCollectionFactory;
        $this->contentCollectionFactory = $contentCollectionFactory;
        $this->itemFactory = $itemFactory;
    }

    /**
     * @param int $storeId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getItems($storeId): array
    {
        $contentTypeCollection = $this->contentTypeCollectionFactory->create()
            ->addFieldToSelect([ContentType::SITEMAP_FREQUENCY, ContentType::SITEMAP_PRIORITY])
            ->addFieldToFilter(ContentType::VISIBILITY, Visibility::VISIBLE)
            ->addFieldToFilter(ContentType::SITEMAP_ENABLE, 1);

        $contentTypeIds = $contentTypeCollection->getAllIds();

        $contentCollection = $this->contentCollectionFactory->create()
            ->addStoreFilter($storeId)
            ->addIsVisibleFilter()
            ->addAttributeToSelect([ModelContent::URL_KEY, ModelContent::UPDATED_AT, ContentType::SITEMAP_PRIORITY])
            ->addAttributeToFilter(ModelContent::CT_ID, $contentTypeIds);

        $items = [];
        /** @var \Blackbird\ContentManager\Model\Content $content */
        foreach ($contentCollection as $content) {
            $currentContentType = $contentTypeCollection->getItemByColumnValue(ContentInterface::CT_ID, $content->getData(ContentInterface::CT_ID));
            $items[] = $this->itemFactory->create([
                'url' => $content->getUrlKey(),
                'updatedAt' => $content->getUpdatedAt(),
                'priority' => $currentContentType->getData(ContentType::SITEMAP_PRIORITY),
                'changeFrequency' => $currentContentType->getData(ContentType::SITEMAP_FREQUENCY),
            ]);
        }

        return $items;
    }
}