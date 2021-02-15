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

namespace BlueFormBuilder\Core\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use BlueFormBuilder\Core\Model\ResourceModel\Form\CollectionFactory;

class FormName extends Column
{
    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * @var CollectionFactory
     */
    protected $formCollectionFactory;

    /**
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param CollectionFactory  $formCollectionFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CollectionFactory $formCollectionFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder            = $urlBuilder;
        $this->formCollectionFactory = $formCollectionFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $formCollection = $this->formCollectionFactory->create();

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['form_id'])) {
                    $label = $item['form_id'];

                    foreach ($formCollection as $_form) {
                        if ($_form->getId() == $item['form_id']) {
                            $url = $this->urlBuilder->getUrl(
                                'blueformbuilder/form/edit',
                                [
                                    'form_id' => $_form->getId()
                                ]
                            );
                            $label = "<a href='" . $url . "' target=\"_self\">" . $_form->getName() . '</a>';
                            break;
                        }
                    }
                    $item['form_id'] = $label;
                    $item['name']    = $label;
                }
            }
        }
        return $dataSource;
    }
}
