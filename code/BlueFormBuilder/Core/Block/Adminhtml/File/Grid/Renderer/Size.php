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

use Magento\Framework\DataObject;

class Size extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    /**
     * @var \BlueFormBuilder\Core\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Backend\Block\Context    $context
     * @param \BlueFormBuilder\Core\Helper\Data $dataHelper
     * @param array                             $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \BlueFormBuilder\Core\Helper\Data $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dataHelper = $dataHelper;
    }

    /**
     * Get value for the cel
     *
     * @param DataObject $row
     * @return string
     */
    public function _getValue(DataObject $row)
    {
        $value = parent::_getValue($row);
        $value = $this->dataHelper->byteconvert($value);
        return $value;
    }
}
