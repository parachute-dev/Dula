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

namespace BlueFormBuilder\Core\Model;

use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;

class SubmissionRepository implements \BlueFormBuilder\Core\Api\SubmissionRepositoryInterface
{
    /**
     * @var Submission[]
     */
    protected $instances = [];

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \BlueFormBuilder\Core\Model\SubmissionFactory
     */
    protected $submissionFactory;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory
     */
    protected $submissionCollectionFactory;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission
     */
    protected $submissionResource;

    /**
     * @var \BlueFormBuilder\Core\Api\Data\SubmissionSearchResultsInterfaceFactory
     */
    protected $submissionSearchResultsFactory;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface                             $storeManager
     * @param \BlueFormBuilder\Core\Model\SubmissionFactory                          $submissionFactory
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Submission                   $submissionResource
     * @param \BlueFormBuilder\Core\Api\Data\SubmissionSearchResultsInterfaceFactory $submissionSearchResultsFactory
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \BlueFormBuilder\Core\Model\SubmissionFactory $submissionFactory,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission $submissionResource,
        \BlueFormBuilder\Core\Api\Data\SubmissionSearchResultsInterfaceFactory $submissionSearchResultsFactory
    ) {
        $this->storeManager                   = $storeManager;
        $this->submissionFactory              = $submissionFactory;
        $this->submissionCollectionFactory    = $submissionCollectionFactory;
        $this->submissionResource             = $submissionResource;
        $this->submissionSearchResultsFactory = $submissionSearchResultsFactory;
    }

    /**
     * Save submission.
     *
     * @param \BlueFormBuilder\Core\Api\Data\SubmissionInterface $submission
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\BlueFormBuilder\Core\Api\Data\SubmissionInterface $submission)
    {
        $storeId = $submission->getStoreId();
        if (!$storeId) {
            $storeId = (int) $this->storeManager->getStore()->getId();
        }

        if ($submission->getId()) {
            $newData    = $submission->getData();
            $submission = $this->get($submission->getId(), $storeId);
            foreach ($newData as $k => $v) {
                $submission->setData($k, $v);
            }
        }

        try {
            $this->submissionResource->save($submission);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __(
                    'Could not save submission: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
        unset($this->instances[$submission->getId()]);
        return $this->get($submission->getId(), $storeId);
    }

    /**
     * Retrieve submission.
     *
     * @param int $submissionId
     * @param int $storeId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($submissionId, $storeId = null)
    {
        $cacheKey = null !== $storeId ? $storeId : 'all';
        if (!isset($this->instances[$submissionId][$cacheKey])) {
            /** @var Submission $submission */
            $submission = $this->submissionFactory->create();
            if (null !== $storeId) {
                $submission->setStoreId($storeId);
            }
            $submission->load($submissionId);

            if (!$submission->getId()) {
                throw NoSuchEntityException::singleField('id', $submissionId);
            }
            $this->instances[$submissionId][$cacheKey] = $submission;
        }
        return $this->instances[$submissionId][$cacheKey];
    }

    /**
     * Delete submission.
     *
     * @param \BlueFormBuilder\Core\Api\Data\SubmissionInterface $submission
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\BlueFormBuilder\Core\Api\Data\SubmissionInterface $submission)
    {
        try {
            $submissionId = $submission->getId();
            $this->submissionResource->delete($submission);
        } catch (\Exception $e) {
            throw new StateException(
                __(
                    'Cannot delete submission with id %1',
                    $submission->getId()
                ),
                $e
            );
        }
        unset($this->instances[$submissionId]);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($submissionId)
    {
        $submission = $this->get($submissionId);
        return  $this->delete($submission);
    }

    /**
     * Load submission data collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->submissionSearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\Collection $collection */
        $collection = $this->submissionCollectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $group) {
            $this->addFilterGroupToCollection($group, $collection);
        }

        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $searchCriteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($searchCriteria->getSortOrders() as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());
        $collection->setPageSize($searchCriteria->getPageSize());
        $submissions = [];

        foreach ($collection as $submission) {
            $submissions[] = $this->get($submission->getId());
        }
        $searchResults->setItems($submissions);
        return $searchResults;
    }

    /**
     * Helper function that adds a FilterGroup to the collection.
     *
     * @param \Magento\Framework\Api\Search\FilterGroup $filterGroup
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Subission\Collection $collection
     * @return void
     * @throws \Magento\Framework\Exception\InputException
     */
    protected function addFilterGroupToCollection(
        \Magento\Framework\Api\Search\FilterGroup $filterGroup,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission\Collection $collection
    ) {
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $collection->addFieldToFilter($filter->getField(), $filter->getValue());
        }
    }
}
