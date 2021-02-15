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

use Magento\Cms\Block\Adminhtml\Page\Grid\Renderer\Action\UrlBuilder;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Framework\Escaper;

class FormActions extends Column
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var [Escaper
     */
    protected $escaper;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \BlueFormBuilder\Core\Helper\Data $dataHelper,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlBuilder $actionUrlBuilder,
        UrlInterface $urlBuilder,
        Escaper $escaper,
        array $components = [],
        array $data = []
    ) {
        $this->actionUrlBuilder = $actionUrlBuilder;
        $this->urlBuilder       = $urlBuilder;
        $this->escaper          = $escaper;
        $this->dataHelper       = $dataHelper;
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
        if (isset($dataSource['data']['items'])) {
            $route   = $this->dataHelper->getRoute();
            $storeId = $this->context->getFilterParam('store_id');
            foreach ($dataSource['data']['items'] as &$item) {
                $name = $this->getData('name');
                $item[$name]['edit'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'blueformbuilder/form/edit',
                        ['form_id' => $item['form_id'], 'store' => $storeId]
                    ),
                    'label'  => __('Edit'),
                    'hidden' => false,
                ];
                $item[$name]['delete'] = [
                    'href' => $this->urlBuilder->getUrl(
                        'blueformbuilder/form/delete',
                        ['form_id' => $item['form_id'], 'store' => $storeId]
                    ),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title'   => __('Delete %1', $name),
                        'message' => __('Are you sure you want to delete a %1 record?', $name)
                    ]
                ];
                $item[$name]['preview'] = [
                    'href' => $this->actionUrlBuilder->getUrl(
                        ($route ? $route . '/' : '') . $item['identifier'],
                        $storeId,
                        isset($item['store_code']) ? $item['store_code'] : null
                    ),
                    'label'  => __('View'),
                    'target' => '_blank'
                ];
                $item['store_id'] = (int) $storeId;
            }
        }
        return $dataSource;
    }
}