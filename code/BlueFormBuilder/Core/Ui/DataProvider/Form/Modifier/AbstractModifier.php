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

namespace BlueFormBuilder\Core\Ui\DataProvider\Form\Modifier;

use Magento\Catalog\Model\Attribute\ScopeOverriddenValue;
use Magezon\UiBuilder\Data\Form\Element\Factory;
use Magezon\UiBuilder\Data\Form\Element\CollectionFactory;
use BlueFormBuilder\Core\Api\Data\FormInterface;

class AbstractModifier implements \Magento\Ui\DataProvider\Modifier\ModifierInterface
{
    const GROUP_CONDITIONAL_SCOPE = 'data';
    const FORM_ELEMENTS           = 'form_elements';

    /**
     * @var Factory
     */
    protected $_factoryElement;

    /**
     * @var CollectionFactory
     */
    protected $_factoryCollection;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var Magezon\UiBuilder\Data\Form\Element\Collection
     */
    protected $_elements;

    protected $group;

    /**
     * @var ScopeOverriddenValue
     */
    private $scopeOverriddenValue;

    /**
     * @param Factory                     $factoryElement    
     * @param CollectionFactory           $factoryCollection 
     * @param \Magento\Framework\Registry $registry          
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        \Magento\Framework\Registry $registry
    ) {
        $this->_factoryElement    = $factoryElement;
        $this->_factoryCollection = $factoryCollection;
        $this->registry           = $registry;
    }

    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Get current form
     *
     * @return \BlueFormBuilder\Core\Model\Form
     * @throws NoSuchEntityException
     */
    public function getCurrentForm()
    {
        return $this->registry->registry('current_form');
    }

    /**
     * Get elements collection
     *
     * @return Collection
     */
    public function getElements()
    {
        if (empty($this->_elements)) {
            $this->_elements = $this->_factoryCollection->create();
        }

        return $this->_elements;
    }

    public function addChildren($elementId, $type, $config = [])
    {
        if (isset($this->_types[$type])) {
            $type = $this->_types[$type];
        }
        $element = $this->_factoryElement->create($type, ['data' => ['config' => $config]]);
        $element->setId($elementId);
        $this->addElement($element);
        return $element;
    }

    public function addFieldset($elementId, $config = [])
    {
        $element = $this->_factoryElement->create('fieldset', ['data' => ['config' => $config]]);
        $element->setId($elementId);
        $this->addElement($element);
        return $element;
    }

    public function addContainer($elementId, $config = [])
    {
        $element = $this->_factoryElement->create('container', ['data' => ['config' => $config]]);
        $element->setId($elementId);
        $this->addElement($element);
        return $element;
    }

    public function addContainerGroup($elementId, $config = [])
    {
        $element = $this->_factoryElement->create('containerGroup', ['data' => ['config' => $config]]);
        $element->setId($elementId);
        $this->addElement($element);
        return $element;
    }

    public function addElement($element)
    {
        $element->setForm($this);
        $this->getElements()->add($element);
        return $this;
    }

    /**
     * Retrieve scope overridden value
     *
     * @return ScopeOverriddenValue
     * @deprecated 101.1.0
     */
    private function getScopeOverriddenValue()
    {
        if (null === $this->scopeOverriddenValue) {
            $this->scopeOverriddenValue = \Magento\Framework\App\ObjectManager::getInstance()->get(
                ScopeOverriddenValue::class
            );
        }

        return $this->scopeOverriddenValue;
    }

    public function getAttribute($attributeCode) {
        $attributes = $this->getCurrentForm()->getAttributes();
        foreach ($attributes as $attribute) {
            if ($attribute->getAttributeCode() == $attributeCode) {
                return $attribute;
            }
        }
        return false;
    }

    /**
     * @param  Get all modal children
     * @return array
     */
    public function getChildren($elements = null)
    {
        if (!$elements) {
            $elements = $this->getElements();
        }
        $form     = $this->getCurrentForm();
        $children = [];
        foreach ($elements as $_element) {
            $id            = $_element->getId();
            $children[$id] = $_element->getElementConfig();
            if ($_element->getElements()->count()) {
                $children[$id]['children'] = $this->getChildren($_element->getElements());
            }
        }
        return $children;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
