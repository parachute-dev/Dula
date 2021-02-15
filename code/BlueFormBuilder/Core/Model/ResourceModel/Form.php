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

namespace BlueFormBuilder\Core\Model\ResourceModel;

use BlueFormBuilder\Core\Api\Data\FormInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;

class Form extends AbstractDb
{
    /**
     * Store manager
     *
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory
     */
    protected $_submissionCollectionFactory;

    /**
     * @param Context                                                                $context                     
     * @param StoreManagerInterface                                                  $storeManager                
     * @param EntityManager                                                          $entityManager               
     * @param MetadataPool                                                           $metadataPool                
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory 
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory,
        $connectionName = null
    ) {
        $this->_storeManager                = $storeManager;
        $this->entityManager                = $entityManager;
        $this->metadataPool                 = $metadataPool;
        $this->_submissionCollectionFactory = $submissionCollectionFactory;
        parent::__construct($context, $connectionName);
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mgz_blueformbuilder_form', 'form_id');
    }

    /**
     * @inheritDoc
     */
    public function getConnection()
    {
        return $this->metadataPool->getMetadata(FormInterface::class)->getEntityConnection();
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$this->getIsUniqueFormToStores($object)) {
            throw new LocalizedException(
                __('A form url with the same properties already exists in the selected store.')
            );
        }

        if (!$object->getBfbFormKey()) {
            $formKey = $object->getId() . '_' . strtr(
                base64_encode(
                    microtime() . $object->getId()
                ),
                '+/=',
                '-_,'
            );
            $object->setBfbFormKey($formKey);
        }

        return $this;
    }

    /**
     * @param AbstractModel $object
     * @param mixed $value
     * @param null $field
     * @return bool|int|string
     * @throws LocalizedException
     * @throws \Exception
     */
    private function getFormId(AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(FormInterface::class);
        if (!is_numeric($value) && $field === null) {
            $field = 'identifier';
        } elseif (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }
        $entityId = $value;
        if ($field != $entityMetadata->getIdentifierField() || $object->getStoreId()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
                ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
                ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $entityId = count($result) ? $result[0] : false;
        }
        return $entityId;
    }

    /**
     * Load an object
     *
     * @param \BlueFormBuilder\Core\Model\Form|AbstractModel $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model id)
     * @return $this
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $formId = $this->getFormId($object, $value, $field);
        if ($formId) {
            $this->entityManager->load($object, $formId);
        }
        return $this;
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \BlueFormBuilder\Core\Model\Form|AbstractModel $object
     * @return Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(FormInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {
            $stores = [(int)$object->getStoreId(), Store::DEFAULT_STORE_ID];

            $select->join(
                ['mbfs' => $this->getTable('mgz_blueformbuilder_form_store')],
                $this->getMainTable() . '.' . $linkField . ' = mbfs.' . $linkField,
                ['store_id']
            )
                ->where('is_active = ?', 1)
                ->where('mbfs.store_id in (?)', $stores)
                ->order('store_id DESC')
                ->limit(1);
        }

        $select->join(
            ['mbfcg' => $this->getTable('mgz_blueformbuilder_form_customer_group')],
            $this->getMainTable() . '.' . $linkField . ' = mbfcg.' . $linkField,
            ['customer_group_id']
        )
            ->where('is_active = ?', 1)
            ->where('mbfcg.customer_group_id = ?', (int)$object->getCustomerGroupId())
            ->order('customer_group_id DESC')
            ->limit(1);

        return $select;
    }

    /**
     * Check for unique of identifier of form to selected store(s).
     *
     * @param AbstractModel $object
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsUniqueFormToStores(AbstractModel $object)
    {
        $entityMetadata = $this->metadataPool->getMetadata(FormInterface::class);
        $linkField = $entityMetadata->getLinkField();

        if ($this->_storeManager->isSingleStoreMode()) {
            $stores = [Store::DEFAULT_STORE_ID];
        } else {
            $stores = (array)$object->getData('store_id');
        }

        $select = $this->getConnection()->select()
            ->from(['cb' => $this->getMainTable()])
            ->join(
                ['cbs' => $this->getTable('mgz_blueformbuilder_form_store')],
                'cb.' . $linkField . ' = cbs.' . $linkField,
                []
            )
            ->where('cb.identifier = ?', $object->getData('identifier'))
            ->where('cbs.store_id IN (?)', $stores);

        if ($object->getId()) {
            $select->where('cb.' . $entityMetadata->getIdentifierField() . ' <> ?', $object->getId());
        }

        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }

        return true;
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $connection = $this->getConnection();

		$entityMetadata = $this->metadataPool->getMetadata(FormInterface::class);
		$linkField      = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['cbs' => $this->getTable('mgz_blueformbuilder_form_store')], 'store_id')
            ->join(
                ['cb' => $this->getMainTable()],
                'cbs.' . $linkField . ' = cb.' . $linkField,
                []
            )
            ->where('cb.' . $entityMetadata->getIdentifierField() . ' = :form_id');

        return $connection->fetchCol($select, ['form_id' => (int)$id]);
    }

    /**
     * @param AbstractModel $object
     * @return $this
     * @throws \Exception
     */
    public function save(AbstractModel $object)
    {
        $this->entityManager->save($object);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function delete(AbstractModel $object)
    {
        $this->entityManager->delete($object);
        return $this;
    }

    /**
     * Get customer groups to which specified item is assigned
     *
     * @param int $formId
     * @return array
     */
    public function lookupCustomerGroups($formId)
    {
        $connection     = $this->getConnection();
        $entityMetadata = $this->metadataPool->getMetadata(\BlueFormBuilder\Core\Api\Data\FormInterface::class);
        $linkField      = $entityMetadata->getLinkField();

        $select = $connection->select()
            ->from(['cps' => $this->getTable('mgz_blueformbuilder_form_customer_group')], 'customer_group_id')
            ->join(
                ['cp' => $this->getMainTable()],
                'cps.form_id = cp.' . $linkField,
                []
            )
            ->where('cp.' . $entityMetadata->getIdentifierField() . ' = :form_id');

        return $connection->fetchCol($select, ['form_id' => (int)$formId]);
    }

    /**
     * Get submissions count in form
     *
     * @param \BlueFormBuilder\Core\Model\Form $form
     * @return int
     */
    public function getSubmissionCount($form)
    {
        $submissionTable = $this->_resources->getTableName('mgz_blueformbuilder_submission');

        $select = $this->getConnection()->select()->from(
            ['main_table' => $submissionTable],
            [new \Zend_Db_Expr('COUNT(main_table.submission_id)')]
        )->where(
            'main_table.form_id = :form_id'
        );

        $bind = ['form_id' => (int)$form->getId()];
        $counts = $this->getConnection()->fetchOne($select, $bind);

        return intval($counts);
    }

    /**
     * Get collection of form submissions
     *
     * @param ing $formId
     * @return \BlueFormBuilder\Core\Model\ResourceModel\Submission\Collection
     */
    public function getSubmissionCollection($formId)
    {
        /** @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\Collection $collection */
        $collection = $this->_submissionCollectionFactory->create();
        $collection->getSelect()->where('form_id = ?', $formId);
        $collection->setOrder('submission_id', 'DESC');
        return $collection;
    }
}
