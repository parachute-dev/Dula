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

namespace BlueFormBuilder\Core\Model\ResourceModel\Form\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use BlueFormBuilder\Core\Api\Data\FormInterface;
use BlueFormBuilder\Core\Model\ResourceModel\Form;
use Magento\Framework\EntityManager\MetadataPool;

class SaveHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var Form
     */
    protected $resourceForm;

    /**
     * @param MetadataPool $metadataPool
     * @param Form $resourceForm
     */
    public function __construct(
        MetadataPool $metadataPool,
        Form $resourceForm
    ) {
        $this->metadataPool = $metadataPool;
        $this->resourceForm = $resourceForm;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     * @throws \Exception
     */
    public function execute($entity, $arguments = [])
    {
        $entityMetadata = $this->metadataPool->getMetadata(FormInterface::class);
        $linkField = $entityMetadata->getLinkField();

        $connection = $entityMetadata->getEntityConnection();

        $oldStores = $this->resourceForm->lookupStoreIds((int)$entity->getId());
        $newStores = (array)$entity->getStoreId();

        $table = $this->resourceForm->getTable('mgz_blueformbuilder_form_store');

        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = [
                $linkField . ' = ?' => (int)$entity->getData($linkField),
                'store_id IN (?)' => $delete,
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newStores, $oldStores);
        if ($insert) {
            $data = [];
            foreach ($insert as $storeId) {
                $data[] = [
                    $linkField => (int)$entity->getData($linkField),
                    'store_id' => (int)$storeId,
                ];
            }
            $connection->insertMultiple($table, $data);
        }

        return $entity;
    }
}