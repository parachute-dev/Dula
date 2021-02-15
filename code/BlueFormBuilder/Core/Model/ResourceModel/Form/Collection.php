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

namespace BlueFormBuilder\Core\Model\ResourceModel\Form;

use BlueFormBuilder\Core\Api\Data\FormInterface;

class Collection extends \BlueFormBuilder\Core\Model\ResourceModel\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'form_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'blueformbuilder_form_collection';

    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject = 'form_collection';

    /**
     * Init collection and determine table names
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\BlueFormBuilder\Core\Model\Form::class, \BlueFormBuilder\Core\Model\ResourceModel\Form::class);
        $this->_map['fields']['store']         = 'store_table.store_id';
        $this->_map['fields']['form_id']       = 'main_table.form_id';
        $this->_map['fields']['customergroup'] = 'customergroup_table.customer_group_id';
    }

    /**
     * Before collection load
     *
     * @return $this
     */
    protected function _beforeLoad()
    {
        $this->_eventManager->dispatch($this->_eventPrefix . '_load_before', [$this->_eventObject => $this]);
        return parent::_beforeLoad();
    }

    /**
     * After collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        $this->_eventManager->dispatch($this->_eventPrefix . '_load_after', [$this->_eventObject => $this]);

        $entityMetadata = $this->metadataPool->getMetadata(FormInterface::class);
        $this->performAfterLoad('mgz_blueformbuilder_form_store', $entityMetadata->getLinkField());

        return parent::_afterLoad();
    }
    
    /**
     * Add active category filter
     *
     * @return $this
     */
    public function addIsActiveFilter()
    {
        $this->addFieldToFilter('is_active', 1);
        $this->_eventManager->dispatch(
            $this->_eventPrefix . '_add_is_active_filter',
            [$this->_eventObject => $this]
        );
        return $this;
    }

    public function addTotalSubmissions()
    {
        $this->getSelect()->joinLeft(
            ['bs' => $this->getTable('mgz_blueformbuilder_submission')],
            'main_table.form_id = bs.form_id',
            ['submissions' => 'COUNT(bs.form_id)']
        )->group('main_table.form_id');

        return $this;
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        $this->performAddStoreFilter($store, $withAdmin);
        return $this;
    }

    public function addCustomerGroupFilter($customerGroupId, $withAdmin = true)
    {
        $this->performAddCustomerGroupAfterLoad($customerGroupId, $withAdmin);
        return $this;
    }

    /**
     * Perform adding filter by store
     *
     * @param int|array|Store $store
     * @param bool $withAdmin
     * @return void
     */
    protected function performAddCustomerGroupAfterLoad($customerGroup)
    {
        if (!is_array($customerGroup)) {
            $customerGroup = [$customerGroup];
        }

        $this->addFilter('customergroup', ['in' => $customerGroup], 'public');
    }

    /**
     * Join store relation table if there is store filter
     *
     * @return void
     */
    protected function _renderFiltersBefore()
    {
        $entityMetadata = $this->metadataPool->getMetadata(FormInterface::class);
        $this->joinStoreRelationTable('mgz_blueformbuilder_form_store', $entityMetadata->getLinkField());
        $this->joinCustomerGroupRelationTable('customergroup_table', 'mgz_blueformbuilder_form_customer_group', 'form_id');
    }

    /**
     * Join store relation table if there is store filter
     *
     * @param string $tableName
     * @param string|null $linkField
     * @return void
     */
    protected function joinCustomerGroupRelationTable($alias, $tableName, $linkField)
    {
        if ($this->getFilter('customergroup')) {
            $this->getSelect()->join(
                [$alias => $this->getTable($tableName)],
                'main_table.' . $linkField . ' = ' . $alias . '.' . $linkField,
                []
            )->group(
                'main_table.' . $linkField
            );
        }
        parent::_renderFiltersBefore();
    }
}
