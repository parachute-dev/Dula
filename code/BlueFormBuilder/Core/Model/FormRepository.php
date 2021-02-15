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

class FormRepository implements \BlueFormBuilder\Core\Api\FormRepositoryInterface
{
    /**
     * @var Form[]
     */
    protected $instances = [];

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \BlueFormBuilder\Core\Model\FormFactory
     */
    protected $formFactory;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory
     */
    protected $formCollectionFactory;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Form
     */
    protected $formResource;

    /**
     * @var \BlueFormBuilder\Core\Api\Data\FormSearchResultsInterfaceFactory
     */
    protected $formSearchResultsFactory;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface                             $storeManager
     * @param \BlueFormBuilder\Core\Model\FormFactory                          $formFactory
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Form                   $formResource
     * @param \BlueFormBuilder\Core\Api\Data\FormSearchResultsInterfaceFactory $formSearchResultsFactory
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \BlueFormBuilder\Core\Model\FormFactory $formFactory,
        \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory,
        \BlueFormBuilder\Core\Model\ResourceModel\Form $formResource,
        \BlueFormBuilder\Core\Api\Data\FormSearchResultsInterfaceFactory $formSearchResultsFactory
    ) {
        $this->storeManager             = $storeManager;
        $this->formFactory              = $formFactory;
        $this->formCollectionFactory    = $formCollectionFactory;
        $this->formResource             = $formResource;
        $this->formSearchResultsFactory = $formSearchResultsFactory;
    }

    /**
     * Save form.
     *
     * @param \BlueFormBuilder\Core\Api\Data\FormInterface $form
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\BlueFormBuilder\Core\Api\Data\FormInterface $form)
    {
        $storeId = $form->getStoreId();
        if (!$storeId) {
            $storeId = (int) $this->storeManager->getStore()->getId();
        }

        if ($form->getId()) {
            $newData    = $form->getData();
            $form = $this->get($form->getId(), $storeId);
            foreach ($newData as $k => $v) {
                $form->setData($k, $v);
            }
        }

        try {
            $this->formResource->save($form);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(
                __(
                    'Could not save form: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
        unset($this->instances[$form->getId()]);
        return $this->get($form->getId(), $storeId);
    }

    /**
     * Retrieve form.
     *
     * @param int $formId
     * @param int $storeId
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($formId, $storeId = null)
    {
        $cacheKey = null !== $storeId ? $storeId : 'all';
        if (!isset($this->instances[$formId][$cacheKey])) {
            /** @var Form $form */
            $form = $this->formFactory->create();
            if (null !== $storeId) {
                $form->setStoreId($storeId);
            }
            $form->load($formId);

            if (!$form->getId()) {
                throw NoSuchEntityException::singleField('id', $formId);
            }
            $this->instances[$formId][$cacheKey] = $form;
        }
        return $this->instances[$formId][$cacheKey];
    }

    /**
     * Delete form.
     *
     * @param \BlueFormBuilder\Core\Api\Data\FormInterface $form
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\BlueFormBuilder\Core\Api\Data\FormInterface $form)
    {
        try {
            $formId = $form->getId();
            $this->formResource->delete($form);
        } catch (\Exception $e) {
            throw new StateException(
                __(
                    'Cannot delete form with id %1',
                    $form->getId()
                ),
                $e
            );
        }
        unset($this->instances[$formId]);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($formId)
    {
        $form = $this->get($formId);
        return  $this->delete($form);
    }

    /**
     * Load form data collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \BlueFormBuilder\Core\Api\Data\FormSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->formSearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \BlueFormBuilder\Core\Model\ResourceModel\Form\Collection $collection */
        $collection = $this->formCollectionFactory->create();

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
        $forms = [];

        foreach ($collection as $form) {
            $forms[] = $this->get($form->getId());
        }
        $searchResults->setItems($forms);
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
        \BlueFormBuilder\Core\Model\ResourceModel\Form\Collection $collection
    ) {
        foreach ($filterGroup->getFilters() as $filter) {
            $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
            $collection->addFieldToFilter($filter->getField(), $filter->getValue());
        }
    }
}
