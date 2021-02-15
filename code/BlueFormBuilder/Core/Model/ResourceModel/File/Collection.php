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

namespace BlueFormBuilder\Core\Model\ResourceModel\File;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'file_id';

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
    protected $_eventPrefix = 'blueformbuilder_file_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'file_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\BlueFormBuilder\Core\Model\File::class, \BlueFormBuilder\Core\Model\ResourceModel\File::class);
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
        if ($field=='size') {
            if (isset($condition['from'])) $condition['from'] = $condition['from'] * 1024;
            if (isset($condition['to'])) $condition['to'] = $condition['to'] * 1024;
        }

        return parent::addFieldToFilter($field, $condition);
    }
}
