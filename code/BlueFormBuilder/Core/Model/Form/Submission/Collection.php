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

namespace BlueFormBuilder\Core\Model\Form\Submission;

class Collection extends \Magento\Framework\Data\Collection\Filesystem
{
	const PREFIX = 'bfb_post_';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @param \Magento\Framework\Data\Collection\EntityFactory $entityFactory
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactory $entityFactory,
        \Magento\Framework\Registry $registry,
        \Magezon\Core\Helper\Data $coreHelper
    ) {
        $this->registry   = $registry;
        $this->coreHelper = $coreHelper;
        parent::__construct($entityFactory);
        $this->setOrder(
            'name',
            self::SORT_ORDER_DESC
        );
    }

    /**
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getCurrentForm()
    {
    	return $this->registry->registry('current_form');
    }

    /**
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getCurrentFrom()
    {
        return $this->registry->registry('current_from');
    }

    /**
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getCurrentTo()
    {
        return $this->registry->registry('current_to');
    }

    /**
     * Load data
     *
     * @param bool $printQuery
     * @param bool $logQuery
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function loadData($printQuery = false, $logQuery = false)
    {
    	if ($this->isLoaded()) {
            return $this;
        }

        $form       = $this->getCurrentForm();
        $collection = $form->getSubmissionCollection()->addCustomerToSelect();
        $from       = $this->getCurrentFrom();
        $to         = $this->getCurrentTo();
        if ($from && $to) {
            $collection->addFieldToFilter('creation_time', array(
                'from' => $from,
                'to'   => $to
            ));
        }

        $elements = $form->getElements();

		$this->_collectedFiles = [];
		foreach ($collection->getItems() as $submission) {
            $post   = $this->coreHelper->unserialize($submission->getPost());
            $values = $submission->getSimpleValues();
            $data   = $values;
			foreach ($elements as $elem) {
                $elemName        = $elem->getElemName();
                $builderElement  = $elem->getBuilderElement();
                $grid            = $builderElement->getData('grid');
                $value           = isset($data[$elemName]) ? $data[$elemName] : null;
                $value           = $elem->prepareGridValue($value);
                $data[$elemName] = $value;
				if (isset($data[$elemName])) {
					$data[self::PREFIX . $elemName] = $post[$elemName];
				} else {
					$data[self::PREFIX . $elemName] = null;
				}
			}
            $data['submission_id']      = $submission->getId();
            $data['increment_id']       = $submission->getIncrementId();
            $data['store_id']           = $submission->getStoreId();
            $data['submission_id']      = $submission->getId();
            $data['bfb_customer_name']  = $submission->getCustomerName();
            $data['bfb_customer_email'] = $submission->getCustomerEmail();
            $data['creation_time']      = $submission->getCreationTime();
            $this->_collectedFiles[]    = $data;
		}

        $this->_collectedDirs = [];
        $this->_generateAndFilterAndSort('_collectedFiles');
        if ($this->_dirsFirst) {
            $this->_generateAndFilterAndSort('_collectedDirs');
            $this->_collectedFiles = array_merge($this->_collectedDirs, $this->_collectedFiles);
        }

        // calculate totals
        $this->_totalRecords = count($this->_collectedFiles);
        $this->_setIsLoaded();

        // paginate and add items
        $from = ($this->getCurPage() - 1) * $this->getPageSize();
        $to = $from + $this->getPageSize() - 1;
        $isPaginated = $this->getPageSize() > 0;

        $cnt = 0;
        foreach ($this->_collectedFiles as $row) {
            $cnt++;
            if ($isPaginated && ($cnt < $from || $cnt > $to)) {
                continue;
            }
            $item = new $this->_itemObjectClass();
            $this->addItem($item->addData($row));
            if (!$item->hasId()) {
                $item->setId($cnt);
            }
        }

        return $this;
    }

    /**
     * With specified collected items:
     *  - generate data
     *  - apply filters
     *  - sort
     *
     * @param string $attributeName '_collectedFiles' | '_collectedDirs'
     * @return void
     */
    private function _generateAndFilterAndSort($attributeName)
    {
        // generate custom data (as rows with columns) basing on the filenames
        foreach ($this->{$attributeName} as $key => $filename) {
            $this->{$attributeName}[$key] = $this->_generateRow($filename);
        }

        // apply filters on generated data
        if (!empty($this->_filters)) {
            foreach ($this->{$attributeName} as $key => $row) {
                if (!$this->_filterRow($row)) {
                    unset($this->{$attributeName}[$key]);
                }
            }
        }

        // sort (keys are lost!)
        if (!empty($this->_orders)) {
            usort($this->{$attributeName}, [$this, '_usort']);
        }
    }

    /**
     * Generate item row basing on the filename
     *
     * @param string $filename
     * @return array
     */
    protected function _generateRow($filename)
    {
        return $filename;
    }

    /**
     * Callback for sorting items
     * Currently supports only sorting by one column
     *
     * @param array $a
     * @param array $b
     * @return int|void
     */
    protected function _usort($a, $b)
    {
        foreach ($this->_orders as $key => $direction) {
            if (!isset($a[$key])) $a[$key] = null;
            if (!isset($b[$key])) $b[$key] = null;
            $result = $a[$key] > $b[$key] ? 1 : ($a[$key] < $b[$key] ? -1 : 0);
            return self::SORT_ORDER_ASC === strtoupper($direction) ? $result : -$result;
            break;
        }
    }
}
