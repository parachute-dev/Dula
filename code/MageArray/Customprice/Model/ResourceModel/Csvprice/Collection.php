<?php

namespace MageArray\Customprice\Model\ResourceModel\Csvprice;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package MageArray\Customprice\Model\ResourceModel\Csvprice
 */
class Collection extends AbstractCollection
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init(
            \MageArray\Customprice\Model\Csvprice::class,
            \MageArray\Customprice\Model\ResourceModel\Csvprice::class
        );
    }
}
