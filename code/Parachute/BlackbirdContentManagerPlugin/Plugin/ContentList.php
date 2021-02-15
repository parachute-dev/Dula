<?php
// Added by Ross @ Parachute Digital Solutions
// Allows for ContentLists to have additional filters based on category attributes
// added by Parachute of the Content Relation type containing an id specified in the url parameters.
// Saves us recreating the collection in the template to filter items. Cuts down on requests.
// Additionally, it allows us to customise the behaviour of the Blackbird_ContentManager classes when necessary.

namespace Parachute\BlackbirdContentManagerPlugin\Plugin;

class ContentList extends \Blackbird\ContentManager\Block\ContentList
{
    // Fields
    protected $_logger;
    protected $_urlInterface;

    // Constructor
    public function __construct(
        \Magento\Framework\UrlInterface $urlInterface,
        \Psr\Log\LoggerInterface $logger)
    {
        $this->_urlInterface = $urlInterface;
        $this->_logger = $logger;
    }

    /**
     * Retrieve the content collection
     *
     * @return \Blackbird\ContentManager\Model\ResourceModel\Content\Collection
     */
    public function afterGetCollection(
        \Blackbird\ContentManager\Block\ContentList $subject, 
        \Blackbird\ContentManager\Model\ResourceModel\Content\Collection $result)
    {
        // Null check
        if($subject == null)
            return $result;

        // We should have gotten a collection back as the result of the default GetCollection() method
        $collection = $result;

        // Request data
        $_baseUrl = $this->_urlInterface->getBaseUrl();
        $_currentUrl = $this->_urlInterface->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);

        // Filtering data
        $_currentCat = !empty($_GET) && !empty($_GET['_cat']) ? intval($_GET['_cat']) : null; // Category
        $_currentPage = !empty($_GET) && !empty($_GET['p']) ? intval($_GET['p']) : null; // Pagination

        // Check to see if we're filtering by category via the '_cat' get parameter
        // We'll do some additional filtering to return items that are marked as that category
        if(!empty($_GET) && !empty($_GET['_cat']))
        {
            // Grab the collection
            $contentType = $subject != null ? $subject->getContentType() : null;
            $identifier = $contentType != null ? $contentType->getIdentifier() : '';
            $prefix = $identifier . '_';

            // Array expressing our filter
            $categoryFilterArr = [
                ['attribute' => $prefix . 'category', 'finset' => $_currentCat]
            ];

            // Filter the collection
            $collection->addAttributeToFilter($categoryFilterArr)->load();
        }

        // Return result
        return $collection;
    }
}