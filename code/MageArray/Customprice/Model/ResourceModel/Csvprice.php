<?php

namespace MageArray\Customprice\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Csvprice
 * @package MageArray\Customprice\Model\ResourceModel
 */
class Csvprice extends AbstractDb
{
    protected $_csvtable;
    /**
     * @var null
     */
    protected $store = null;
    /**
     * @var null
     */
    protected $connection = null;

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('magearray_csvprice', 'id');
        $this->_csvtable = $this->getTable('magearray_csvprice');
    }

    /**
     * Csvprice constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\App\ResourceConnection $resource
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->_resource = $resource;
        parent::__construct($context);
    }

    public function addCsvFilter($product_id, $option_sku = '')
    {
        $connection = $this->getConnection();
        if ($option_sku) {
            $select = $connection->select()
                    ->from(['o' =>  $this->_csvtable])
                    ->where('o.product_id=?', $product_id)
                    ->where('o.option_sku=?', $option_sku);
        } else {
            $select = $connection->select()
                    ->from(['o' =>  $this->_csvtable])
                    ->where('o.product_id=?', $product_id);
        }
        return $connection->fetchRow($select);
    }
}
