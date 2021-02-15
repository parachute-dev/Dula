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

namespace BlueFormBuilder\Core\Model\ResourceModel\Submission;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'submission_id';

    /**
     * Load data for preview flag
     *
     * @var bool
     */
    protected $_previewFlag;

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'blueformbuilder_submission_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'submission_collection';

    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    protected $customerCollectionFactory;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface        $entityFactory
     * @param \Psr\Log\LoggerInterface                                         $logger
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface     $fetchStrategy
     * @param \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory
     * @param \Magento\Framework\Event\ManagerInterface                        $eventManager
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null              $connection
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null        $resource
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory $customerCollectionFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct(
            $entityFactory,
            $logger,
            $fetchStrategy,
            $eventManager,
            $connection,
            $resource
        );
        $this->customerCollectionFactory = $customerCollectionFactory;
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\BlueFormBuilder\Core\Model\Submission::class, \BlueFormBuilder\Core\Model\ResourceModel\Submission::class);
    }

    public function addCustomerToSelect()
    {
        $resource = $this->_resource;

        $connection = $resource->getConnection();

        $select = $connection->select()->from(
            $resource->getTable('eav_entity_type'),
            []
        )->join(
            $resource->getTable('eav_attribute'),
            $resource->getTable('eav_attribute') . '.entity_type_id = ' . $resource->getTable('eav_entity_type') . '.entity_type_id',
            $resource->getTable('eav_attribute') . '.attribute_id'
        )->where(
            $resource->getTable('eav_entity_type') . '.entity_type_code = ?',
            \Magento\Catalog\Model\Product::ENTITY
        )->where(
            $resource->getTable('eav_attribute') . '.attribute_code = ?',
            'name'
        );
        $result = $connection->fetchRow($select);

        if (!empty($result)) {
            $prodNameAttrId = $result['attribute_id'];
            $this->getSelect()->joinLeft(
                [
                    'cpev' => $resource->getTable('catalog_product_entity_varchar')
                ],
                'main_table.product_id = cpev.entity_id AND cpev.attribute_id = ' . $prodNameAttrId,
                [
                    'value' => 'value'
                ]
            );
        }
        $this->getSelect()->joinLeft(
            [
                'cpe' => $resource->getTable('catalog_product_entity')
            ],
            'main_table.product_id = cpe.entity_id',
            [
                'sku' => 'sku'
            ]
        );

        $this->getSelect()->joinLeft(
            [
                'cgf' =>  $resource->getTable('customer_grid_flat')
            ],
            'main_table.customer_id=cgf.entity_id',
            [
                'customer_name'  => 'name',
                'customer_email' => 'email'
            ]
        );

        $this->getSelect()->group('main_table.submission_id');

        return $this;
    }

    /**
     * Add field filter to collection
     *
     * @see self::_getConditionSql for $condition
     *
     * @param string|array $field
     * @param null|string|array $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition = null)
    {
        $includesFields = ['form_id', 'creation_time', 'store_id', 'submission_id'];
        if (in_array($field, $includesFields)) {
            $field = 'main_table.' . $field;
        }

        if ($field === 'customer_name' && $condition['like'] === '%Guest%') {
            $this->getSelect()->where('main_table.customer_id IS NULL');
            return $this;
        }

        if ($field == 'customer_email' || $field == 'customer_name') {
            $collection = $this->customerCollectionFactory->create();
            if ($field == 'customer_email') {
                $collection->addFieldToFilter('email', $condition);
                $customerIds = (array) $collection->getAllIds();
            } else {
                $collection->addAttributeToFilter(
                    [
                        ['attribute'=>'firstname', 'like' => trim($condition['like'])],
                        ['attribute'=>'lastname', 'like' => trim($condition['like'])],
                    ]
                );

                $customerIds = (array) $collection->getAllIds();
                if (empty($customerIds)) {
                    $collection = $this->customerCollectionFactory->create();
                    $collection->addNameToSelect();
                    $name = str_replace("%", "", $condition['like']);
                    foreach ($collection as $customer) {
                        if ($customer->getName() == $name || (strpos($customer->getName(), $name) !== false)) {
                            $customerIds[] = $customer->getId();
                        }
                    }
                }
            }
            if (!empty($customerIds)) {
                $connection = $this->getConnection();
                $select = $connection->select()->from(
                    $this->getTable('blueformbuilder_submission'),
                    'submission_id'
                )->where('customer_id IN (' . implode(',', $customerIds) . ')');
                $customerFileIds = (array) $connection->fetchCol($select);
                if (!empty($customerFileIds)) {
                    $this->_select->where('submission_id IN (' . implode(',', $customerFileIds) . ')');
                } else {
                    $this->_select->where('1 = 0');
                }
            } else {
                $this->_select->where('1 = 0');
            }
            return $this;
        }

        if (is_array($field)) {
            $conditions = [];
            foreach ($field as $key => $value) {
                $conditions[] = $this->_translateCondition($value, isset($condition[$key]) ? $condition[$key] : null);
            }

            $resultCondition = '(' . implode(') ' . \Magento\Framework\DB\Select::SQL_OR . ' (', $conditions) . ')';
        } else {
            $resultCondition = $this->_translateCondition($field, $condition);
        }

        $this->_select->where($resultCondition, null, \Magento\Framework\DB\Select::TYPE_CONDITION);

        return $this;
    }
}
