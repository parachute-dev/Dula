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

namespace BlueFormBuilder\Core\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class FormList implements OptionSourceInterface
{
    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Constructor
     *
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory
     */
    public function __construct(\BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $collection = $this->collectionFactory->create();
        $options = [];
        foreach ($collection as $form) {
            $options[] = [
                'label' => $form->getName(),
                'value' => $form->getId()
            ];
        }
        return $options;
    }
}
