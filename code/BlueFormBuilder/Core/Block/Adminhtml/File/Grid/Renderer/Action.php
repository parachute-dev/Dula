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

namespace BlueFormBuilder\Core\Block\Adminhtml\File\Grid\Renderer;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Action
{
    /**
     * @var \Magento\Framework\Url
     */
    protected $_frontendUrlBuilder;

    /**
     * @param \Magento\Backend\Block\Context           $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Framework\Url                   $frontendUrlBuilder
     * @param array                                    $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Url $frontendUrlBuilder,
        array $data = []
    ) {
        parent::__construct($context, $jsonEncoder, $data);
        $this->_frontendUrlBuilder = $frontendUrlBuilder;
    }

    /**
     * Render grid column
     *
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
        $actions = [];
        $actions[] = [
            'url' => $this->_frontendUrlBuilder->getUrl('blueformbuilder/file/download', [
                'id'      => $row->getElementId(),
                'key'     => $row->getFileHash(),
                'backend' => true
            ]),
            'popup'   => true,
            'caption' => __('Download')
        ];

        $this->getColumn()->setActions($actions);

        return parent::render($row);
    }

    /**
     * Get escaped value
     *
     * @param string $value
     * @return string
     */
    protected function _getEscapedValue($value)
    {
        return addcslashes(htmlspecialchars($value), '\\\'');
    }

    /**
     * Render single action as link html
     *
     * @param array $action
     * @param \Magento\Framework\DataObject $row
     * @return string
     */
    protected function _toLinkHtml($action, \Magento\Framework\DataObject $row)
    {
        $actionCaption = '';
        $this->_transformActionData($action, $actionCaption, $row);
        return '<a href="' . $action['href'] . '" target="_blank">' . $actionCaption . '</a>';
    }
}
