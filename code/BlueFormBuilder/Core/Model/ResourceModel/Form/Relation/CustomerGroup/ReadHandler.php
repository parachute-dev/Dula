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

use BlueFormBuilder\Core\Model\ResourceModel\Form;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\EntityManager\Operation\ExtensionInterface;

class ReadHandler implements ExtensionInterface
{
    /**
     * @var MetadataPool
     */
    protected $metadataPool;

    /**
     * @var Form
     */
    protected $formResource;

    /**
     * @param MetadataPool $metadataPool
     * @param Form $formResource
     */
    public function __construct(
        MetadataPool $metadataPool,
        Form $formResource
    ) {
        $this->metadataPool = $metadataPool;
        $this->formResource = $formResource;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return object
     */
    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $customerGroups = $this->formResource->lookupCustomerGroups((int)$entity->getId());
            $entity->setData('customer_group_id', $customerGroups);
        }
        return $entity;
    }
}
