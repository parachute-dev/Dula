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

namespace BlueFormBuilder\Core\Model\ResourceModel\Form\Relation\CustomerGroup;

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
        $linkField      = $entityMetadata->getLinkField();
        $connection     = $entityMetadata->getEntityConnection();
        $oldGroups      = $this->resourceForm->lookupCustomerGroups((int)$entity->getId());
        $newGroups      = (array)$entity->getCustomerGroupId();

        $table  = $this->resourceForm->getTable('mgz_blueformbuilder_form_customer_group');
        $delete = array_diff($oldGroups, $newGroups);
        if ($delete) {
            $where = [
                'form_id = ?' => (int)$entity->getData($linkField),
                'customer_group_id IN (?)' => $delete
            ];
            $connection->delete($table, $where);
        }

        $insert = array_diff($newGroups, $oldGroups);
        if ($insert) {
            $data = [];
            foreach ($insert as $k => $groupId) {
                if ($groupId !== '') {
                    $data[] = [
                        'form_id' => (int)$entity->getData($linkField),
                        'customer_group_id' => (int)$groupId
                    ];
                }
            }
            if (!empty($data)) {
                $connection->insertMultiple($table, $data);
            }
        }

        return $entity;
    }
}
