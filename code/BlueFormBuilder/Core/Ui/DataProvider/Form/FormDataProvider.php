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

namespace BlueFormBuilder\Core\Ui\DataProvider\Form;

use BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

class FormDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    const META_CONFIG_PATH = '/arguments/data/config';

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Form\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @var \Magezon\Builder\Helper\Data
     */
    protected $builderHelper;

    /**
     * @var \BlueFormBuilder\Core\Helper\Form
     */
    protected $formHelper;

    /**
     * @param string                            $name                  
     * @param string                            $primaryFieldName      
     * @param string                            $requestFieldName      
     * @param \Magento\Framework\Registry       $registry              
     * @param CollectionFactory                 $formCollectionFactory 
     * @param DataPersistorInterface            $dataPersistor         
     * @param \Magezon\Core\Helper\Data         $coreHelper            
     * @param \Magezon\Builder\Helper\Data      $builderHelper         
     * @param \BlueFormBuilder\Core\Helper\Form $formHelper            
     * @param PoolInterface                     $pool                  
     * @param array                             $meta                  
     * @param array                             $data                  
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\RequestInterface $request,
        CollectionFactory $formCollectionFactory,
        DataPersistorInterface $dataPersistor,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magezon\Core\Helper\Data $coreHelper,
        \Magezon\Builder\Helper\Data $builderHelper,
        \BlueFormBuilder\Core\Helper\Form $formHelper,
        PoolInterface $pool,
        array $meta = [],
        array $data = []
    ) {
        $this->collection    = $formCollectionFactory->create();
        $this->registry      = $registry;
        $this->dataPersistor = $dataPersistor;
        $this->request       = $request;
        $this->moduleManager = $moduleManager;
        $this->coreHelper    = $coreHelper;
        $this->builderHelper = $builderHelper;
        $this->formHelper    = $formHelper;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
        $this->pool = $pool;
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $form = $this->getCurrentForm();
        if ($form && $form->getId()) {
            $formData = $form->getData();

            /** @var ModifierInterface $modifier */
            foreach ($this->pool->getModifiersInstances() as $modifier) {
                $formData = $modifier->modifyData($formData);
            }
            $this->loadedData[$form->getId()] = $formData;
        }

        $data = $this->dataPersistor->get('current_form');
        if (!empty($data)) {
            $form = $this->collection->getNewEmptyItem();
            $form->setData($data);
            $this->loadedData[$form->getId()] = $form->getData();
            $this->dataPersistor->clear('current_form');
        }
        if (is_array($this->loadedData)) {
            foreach ($this->loadedData as &$row) {
                if (is_array($row)) {
                    foreach ($row as $key => &$_item) {
                        if ($key!=='profile' && $key!=='conditional') {
                            $_item = $this->coreHelper->unserialize($_item);
                        }
                    }
                }
            }
        } 
        if ($this->loadedData==NULL) {
            $this->loadedData[$form->getId()] = $this->formHelper->getFormDefaultData();
        }
        return $this->loadedData;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        if (!isset($meta['plugins']['children'])) {
            $meta['plugins']['children'] = [];
            if ($this->moduleManager->isEnabled('BlueFormBuilder_GDPR')) {
                $meta['plugins']['children']['gdpr'] = [];
            }
        }

        if (!isset($meta['settings']['children']['email'])) {
            $meta['settings']['children']['email'] = [
                'children' => [
                    'admin'    => [],
                    'customer' => []
                ]
            ];
        }

        if (!isset($meta['settings']['children']['success_message'])) {
            $meta['settings']['children']['success_message'] = [
                'children' => [
                    
                ]
            ];
        }

        /** @var ModifierInterface $modifier */
        foreach ($this->pool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        $meta = $this->prepareMeta($meta);

        $form = $this->getCurrentForm();
        $meta = $this->generatedUrlKey($meta, 'identifier');
        $meta = $this->processActiveTabs($meta);

        return $meta;
    }

    public function processActiveTabs($meta)
    {
        if ($this->request->getParam('active')) {
            $actives = explode(',', $this->request->getParam('active'));

            foreach ($actives as $active) {
                if ($active) {
                    $tabs       = explode('_', $active);
                    $activePath = null;
                    foreach ($tabs as $k => &$tab) {
                        if ($tab == 'builder') {
                            $tab = 'builder';
                        }
                        if ($tab == 'conditions') {
                            $tab = 'conditions_container';
                        }
                        if ($tab == 'formproducts') {
                            $tab = 'formproducts_container';
                        }
                        if ($tab == 'javascript') {
                            $tab = 'custom_javascript';
                        }
                        if ($tab == 'successmessage') {
                            $tab = 'success_message';
                        }
                        if ($tab == 'customergroups') {
                            $tab = 'customer_groups';
                        }
                        if ($tab == 'customcss') {
                            $tab = 'custom_css';
                        }
                        if ($tab == 'customjavascript') {
                            $tab = 'custom_javascript';
                        }
                        if ($tab) {
                            if ($activePath && $k > 0) {
                                $activePath .= '/children';
                            }
                            $path = $this->builderHelper->getArrayManager()->findPath($tab, $meta, $activePath);
                            $meta = $this->builderHelper->getArrayManager()->merge(
                                $path,
                                $meta,
                                [
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'initializeFieldsetDataByDefault' => true,
                                                'opened'                          => true
                                            ]
                                        ]
                                    ]
                                ]
                            );
                        }
                    }
                }
            } 
        }
        return $meta;
    }


    /**
     * Get current form
     *
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getCurrentForm()
    {
        return $this->registry->registry('current_form');
    }

    /**
     * Add links for fields depends of product name
     *
     * @param array $meta
     * @return array
     */
    protected function generatedUrlKey($meta, $urlKey = 'identifier', $name = 'name')
    {
        $listenerPath  = $this->builderHelper->getArrayManager()->findPath($urlKey, $meta, null, 'children');
        $importsConfig = [
            'mask'        => '{{name}}',
            'component'   => 'Magezon_Core/js/components/generated-urlkey',
            'allowImport' => !$this->getCurrentForm()->getId(),
            'elementTmpl' => 'ui/form/element/input'
        ];

        $meta       = $this->builderHelper->getArrayManager()->merge($listenerPath . static::META_CONFIG_PATH, $meta, $importsConfig);
        $urlKeyPath = $this->builderHelper->getArrayManager()->findPath($urlKey, $meta, null, 'children');

        $meta = $this->builderHelper->getArrayManager()->merge(
            $urlKeyPath . static::META_CONFIG_PATH,
            $meta,
            [
                'autoImportIfEmpty' => true
            ]
        );

        $namePath = $this->builderHelper->getArrayManager()->findPath($name, $meta, null, 'children');

        return $this->builderHelper->getArrayManager()->merge(
            $namePath . static::META_CONFIG_PATH,
            $meta,
            [
                'valueUpdate' => 'keyup'
            ]
        );
    }
}
