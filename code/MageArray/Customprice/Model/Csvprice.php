<?php

namespace MageArray\Customprice\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * Class Csvprice
 * @package MageArray\Customprice\Model
 */
class Csvprice extends AbstractModel
{

    /**
     *
     */
    protected function _construct()
    {
        $this->_init(\MageArray\Customprice\Model\ResourceModel\Csvprice::class);
    }
}
