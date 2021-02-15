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

use Magento\Framework\DB\Select;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Framework\EntityManager\EntityManager;
use BlueFormBuilder\Core\Api\Data\SubmissionInterface;

class Submission extends AbstractDb
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory
     */
    protected $_fileCollectionFactory;

    /**
     * @var \BlueFormBuilder\Core\Model\Form
     */
    protected $_form;

    /**
     * @param Context                                                          $context
     * @param EntityManager                                                    $entityManager
     * @param MetadataPool                                                     $metadataPool
     * @param \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory
     * @param \BlueFormBuilder\Core\Model\FormFactory                          $formFactory
     * @param string                                                           $connectionName
     */
    public function __construct(
        Context $context,
        EntityManager $entityManager,
        MetadataPool $metadataPool,
        \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory,
        \BlueFormBuilder\Core\Model\FormFactory $formFactory,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->entityManager          = $entityManager;
        $this->metadataPool           = $metadataPool;
        $this->_fileCollectionFactory = $fileCollectionFactory;
        $this->formFactory            = $formFactory;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('mgz_blueformbuilder_submission', 'submission_id');
    }

    /**
     * Load an object
     *
     * @param AbstractModel $object
     * @param mixed $value
     * @param string $field field to load by (defaults to model id)
     * @return $this
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        $submissionId = $this->getSubmissionId($object, $value, $field);
        if ($submissionId) {
            $this->entityManager->load($object, $submissionId);
        }
        return $this;
    }

    /**
     * @inheritDoc
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
     * @param AbstractModel $object
     * @param string $value
     * @param string|null $field
     * @return bool|int|string
     * @throws LocalizedException
     * @throws \Exception
     */
    private function getSubmissionId(AbstractModel $object, $value, $field = null)
    {
        $entityMetadata = $this->metadataPool->getMetadata(SubmissionInterface::class);
        if (!$field) {
            $field = $entityMetadata->getIdentifierField();
        }
        $submissionId = $value;
        if ($field != $entityMetadata->getIdentifierField()) {
            $select = $this->_getLoadSelect($field, $value, $object);
            $select->reset(Select::COLUMNS)
            ->columns($this->getMainTable() . '.' . $entityMetadata->getIdentifierField())
            ->limit(1);
            $result = $this->getConnection()->fetchCol($select);
            $value  = count($result) ? $result[0] : $value;
            $submissionId = count($result);
        }
        return $submissionId;
    }

    /**
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($object->getSubmissionHash() == '') {
            $object->setSubmissionHash($this->getSubmissionHash());
        }
        return parent::_beforeSave($object);
    }

    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (!$object->getIncrementId()) {
            $object->setIncrementId($this->getIncrementId($object));
            $this->_saveIncrementId($object);
        }
        return parent::_afterSave($object);
    }

    /**
     * Update increment id
     *
     * @param \BlueFormBuilder\Core\Model\Submission $object
     * @return $this
     */
    protected function _saveIncrementId($object)
    {
        if ($object->getId()) {
            $this->getConnection()->update(
                $this->getMainTable(),
                ['increment_id' => $object->getIncrementId()],
                ['submission_id = ?' => $object->getId()]
            );
        }
        return $this;
    }

    /**
     * @param  int $id
     * @return string
     */
    public function getIncrementId($object)
    {
        $incrementPadLength = \BlueFormBuilder\Core\Model\Submission::INCREMENT_PAD_LENGTH;
        $idLength           = strlen($object->getId());
        $incrementId        = '';
        for ($i=0; $i < $incrementPadLength; $i++) {
            if ($i < ($incrementPadLength - $idLength)) {
                $incrementId .= '0';
            }
        }
        $form        = $object->getForm();
        $incrementId = $form->getSubmissionPrefix() . $incrementId . $object->getId();

        return $incrementId;
    }

    /**
     * Get collection of submission files
     *
     * @param ing $submissionId
     * @return \BlueFormBuilder\Core\Model\ResourceModel\File\Collection
     */
    public function getFileCollection($submissionId)
    {
        /** @var \BlueFormBuilder\Core\Model\ResourceModel\File\Collection $collection */
        $collection = $this->_fileCollectionFactory->create();
        $collection->getSelect()->where('submission_id = ?', $submissionId);
        $collection->setOrder('file_id', 'DESC');
        return $collection;
    }

    /**
     * @param \Magento\Framework\DataObject $object
     * @return $this
     */
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $fileCollection = $object->getFileCollection();
        foreach ($fileCollection as $_file) {
            $_file->delete();
        }
        parent::_beforeDelete($object);
        return $this;
    }

    public function getForm()
    {
        if ($this->_form === null) {
            $form = $this->formFactory->create();
            $form->load($this->getFormId());
            $this->_form = $form;
        }

        return $this->_form;
    }

    /**
     * @return string
     */
    private function getSubmissionHash()
    {
        $hash = strtr(
                base64_encode(
                    microtime()
                    ),
                '+/=',
                '-_,'
            );
        return $hash;
    }
}
