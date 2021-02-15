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

namespace BlueFormBuilder\Core\Ui\DataProvider;

use Magento\Framework\App\RequestInterface;
use BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory;

class SubmissionDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var BlueFormBuilder\Core\Model\ResourceModel\Submission\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]
     */
    protected $addFieldStrategies;

    /**
     * @var \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;

    /**
     * @param string            $name                
     * @param string            $primaryFieldName    
     * @param string            $requestFieldName    
     * @param CollectionFactory $collectionFactory   
     * @param RequestInterface  $request             
     * @param array             $addFieldStrategies  
     * @param array             $addFilterStrategies 
     * @param array             $meta                
     * @param array             $data                
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->collection->addCustomerToSelect();
        if ($formId = $request->getParam('current_form_id', 0)) {
            $this->collection->addFieldToFilter('form_id', $request->getParam('current_form_id', 0));
        }
        $this->addFieldStrategies  = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
        $this->request = $request;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }

        $items = $this->getCollection()->toArray();

        return [
            'totalRecords' => $this->getCollection()->getSize(),
            'items'        => $items['items']
        ];
    }

    /**
     * Add field to select
     *
     * @param string|array $field
     * @param string|null $alias
     * @return void
     */
    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]->addField($this->getCollection(), $field, $alias);
        } else {
            parent::addField($field, $alias);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if ($filter->getField() == 'form_id' && $this->request->getParam('current_form_id', 0)) {
            return;
        }
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } else {
            parent::addFilter($filter);
        }
    }
}
