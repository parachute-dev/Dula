<?php
/**
 * ContentList
 *
 * @copyright Copyright © 2019 Blackbird. All rights reserved.
 * @author    etienne (Blackbird Team)
 */
declare(strict_types=1);

namespace Blackbird\ContentManager\Model\Sitemap\ItemProvider;

use Blackbird\ContentManager\Api\Data\ContentListInterface;
use Magento\Sitemap\Model\ItemProvider\ItemProviderInterface;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;
use Blackbird\ContentManager\Model\Config\Source\ContentType\Visibility;
use Blackbird\ContentManager\Model\ContentType;
use Blackbird\ContentManager\Model\ResourceModel\ContentType\CollectionFactory as ContentTypeCollectionFactory;
use Blackbird\ContentManager\Model\ResourceModel\ContentList\CollectionFactory as ContentListCollectionFactory;

class ContentList implements ItemProviderInterface
{
    /**
     * @var \Blackbird\ContentManager\Model\ResourceModel\ContentType\CollectionFactory
     */
    private $contentTypeCollectionFactory;

    /**
     * @var \Blackbird\ContentManager\Model\ResourceModel\ContentList\CollectionFactory
     */
    private $contentListCollectionFactory;

    /**
     * @var \Magento\Sitemap\Model\SitemapItemInterfaceFactory
     */
    private $itemFactory;

    /**
     * ContentList constructor.
     *
     * @param \Blackbird\ContentManager\Model\ResourceModel\ContentType\CollectionFactory $contentTypeCollectionFactory
     * @param \Blackbird\ContentManager\Model\ResourceModel\ContentList\CollectionFactory $contentListCollectionFactory
     * @param \Magento\Sitemap\Model\SitemapItemInterfaceFactory $itemFactory
     */
    public function __construct(
        ContentTypeCollectionFactory $contentTypeCollectionFactory,
        ContentListCollectionFactory $contentListCollectionFactory,
        SitemapItemInterfaceFactory $itemFactory
    )
    {
        $this->contentTypeCollectionFactory = $contentTypeCollectionFactory;
        $this->contentListCollectionFactory = $contentListCollectionFactory;
        $this->itemFactory = $itemFactory;
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getItems($storeId): array
    {
        $contentTypeCollection = $this->contentTypeCollectionFactory->create()
            ->addFieldToSelect([ContentType::SITEMAP_FREQUENCY, ContentType::SITEMAP_PRIORITY])
            ->addFieldToFilter(ContentType::VISIBILITY, Visibility::VISIBLE)
            ->addFieldToFilter(ContentType::SITEMAP_ENABLE, 1);

        $contentTypeIds = $contentTypeCollection->getAllIds();

        $contentListCollection = $this->contentListCollectionFactory->create()
            ->addFieldToSelect([ContentListInterface::URL_KEY, ContentListInterface::CT_ID])
            ->addFieldToFilter(ContentListInterface::CT_ID, $contentTypeIds)
            ->addFieldToFilter(ContentListInterface::STATUS, 1);

        $items = [];
        /** @var \Blackbird\ContentManager\Model\ContentList $contentList */
        foreach ($contentListCollection as $contentList) {
            $currentContentType = $contentTypeCollection->getItemByColumnValue(ContentListInterface::CT_ID, $contentList->getData(ContentListInterface::CT_ID));
            $items[] = $this->itemFactory->create([
                'url' => $contentList->getUrlKey(),
                'updatedAt' => $contentList->getUpdatedAt(),
                'priority' => $currentContentType->getData(ContentType::SITEMAP_PRIORITY),
                'changeFrequency' => $currentContentType->getData(ContentType::SITEMAP_FREQUENCY),
            ]);
        }

        return $items;
    }
}