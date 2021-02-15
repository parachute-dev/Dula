<?php
declare(strict_types=1);

namespace Blackbird\ContentManager\Block\Adminhtml\Category\Widget;

use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Catalog\Model\ResourceModel\Category\Collection;
use Magento\Framework\AuthorizationInterface;
use Magento\Framework\Data\Form\Element\CollectionFactory;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\Multiselect;
use Magento\Framework\Escaper;

/**
 * Class Chooser
 * @package Blackbird\ContentManager\Block\Adminhtml\Category\Widget
 */
class Chooser extends Multiselect{

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    private $authorization;

    /**
     * @var array
     */
    private $data;

    /**
     * Chooser constructor.
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory
     * @param \Magento\Framework\AuthorizationInterface $authorization
     * @param array $data
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        Escaper $escaper,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $collectionFactory,
        AuthorizationInterface $authorization,
        array $data = []
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->authorization = $authorization;
        $this->data = $data;
        parent::__construct($factoryElement, $factoryCollection, $escaper, $data);
    }

    /**
     * Get field html
     *
     * @return string
     */
    public function getElementHtml():string
    {
        $html = '<div class="admin__field-control admin__control-grouped category-field-chooser">';
        $html .= '<div class="admin__field" data-bind="scope:\''.$this->getUniqComponentName().'\'" data-index="index">';
        $html .= '<!-- ko foreach: elems() -->';
        $html .= '<input name="'.$this->data['name'].'" data-bind="value: value" style="display: none"/>';
        $html .= '<!-- ko template: elementTmpl --><!-- /ko -->';
        $html .= '<!-- /ko -->';
        $html .= '</div></div>';

        $html .= $this->getAfterElementHtml();
        return $html;
    }

    /**
     * Get Categories Tree
     *
     * @return array
     */
    public function getCategoriesTree():array
    {
        /* @var $collection \Magento\Catalog\Model\ResourceModel\Category\Collection */
        $collection = $this->collectionFactory->create()->addAttributeToSelect('name')->addAttributeToSort('position','asc');

        $categoryById = [
            CategoryModel::TREE_ROOT_ID => [
                'value'    => CategoryModel::TREE_ROOT_ID,
                'optgroup' => null,
            ],
        ];

        foreach ($collection as $category) {
            foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                if (!isset($categoryById[$categoryId])) {
                    $categoryById[$categoryId] = ['value' => $categoryId];
                }
            }

            $categoryById[$category->getId()]['is_active'] = 1;
            $categoryById[$category->getId()]['label'] = $category->getName();
            $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
        }

        return $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
    }

    /**
     * Get values for select
     *
     * @return array
     */
    public function getValues():array
    {
        $collection = $this->_getCategoriesCollection();
        $values = $this->getValue();
        $options = [];
        if (!empty($values)) {
            if (!is_array($values)) {
                $values = explode(',', $values);
            }
            $collection->addAttributeToSelect('name');
            $collection->addIdFilter($values);

            foreach ($collection as $category) {
                $options[]= $category->getId();
            }
        }
        return $options;
    }

    /**
     * Get categories collection
     *
     * @return Collection
     */
    protected function _getCategoriesCollection()
    {
        return $this->collectionFactory->create();
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAfterElementHtml():string
    {
        $html = '<script type="text/x-magento-init">
            {
                "*": {
                    "Magento_Ui/js/core/app": {
                        "components": {
                            "'.$this->getUniqComponentName().'": {
                                "component": "uiComponent",
                                "children": {
                                    "select_category": {
                                        "component": "Blackbird_ContentManager/js/components/new-category",
                                        "config": {
                                            "filterOptions": true,
                                            "disableLabel": true,
                                            "chipsEnabled": true,
                                            "levelsVisibility": "1",
                                            "elementTmpl": "ui/grid/filters/elements/ui-select",
                                            "options": ' . json_encode($this->getCategoriesTree()) . ',
                                            "value": ' . json_encode($this->getValues()) . ',
                                            "listens": {
                                                "index=create_category:responseData": "setParsed",
                                                "newOption": "toggleOptionSelected"
                                            },
                                            "config": {
                                                "dataScope": "select_category",
                                                "sortOrder": 10
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        </script>';

        return $html;
    }

    /**
     * @return string
     */
    private function getUniqComponentName():string{
        return 'content_category_'.str_replace(['[',']'] ,'',$this->data['name']);
    }
}