<?php

// Added by Ross@Parachute
// A block that displays a list of content items of the type "testimonial" in a slick slider

namespace Parachute\BlackbirdContentManagerPlugin\Block;

use Blackbird\ContentManager\Block\Content\Widget;
use Blackbird\ContentManager\Model\Content;
use Blackbird\ContentManager\Model\ContentType;
use Blackbird\ContentManager\Model\ContentType\CustomField;

class TestimonialsSlider extends \Blackbird\ContentManager\Block\Content\Widget\ContentList
{
    // Methods
    /**
     * Prepare the content collection
     *
     * @return \Blackbird\ContentManager\Model\ResourceModel\Content\Collection
     * @throws \Magento\Framework\Exception\LocalizedException
     * @todo code cleanup
     */
    protected function prepareContentCollection()
    {
        $collection = $this->_contentCollectionFactory->create();

        // Filter by : store, content type, status
        $collection->addStoreFilter()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter(Content::STATUS, 1)
            ->addContentTypeFilter('testimonial');


        // Set the sort order
        if ($this->hasData('order_field')) {
            $orderField = $this->getOrderField();
            $sortOrder = $this->hasData('sort_order') ? $this->getSortOrder() : 'ASC';

            // Add multi sort oder
            if (is_array($orderField)) {
                if (is_array($sortOrder)) {
                    foreach ($orderField as $key => $field) {
                        $collection->addAttributeToSort($field, $sortOrder[$key]);
                    }
                } else {
                    foreach ($orderField as $field) {
                        $collection->addAttributeToSort($field, $sortOrder);
                    }
                }
            } else {
                $collection->addAttributeToSort($orderField, $sortOrder);
            }
        } else {
            $collection->addAttributeToSort('created_at', 'DESC');
        }

        return $collection;
    }
}