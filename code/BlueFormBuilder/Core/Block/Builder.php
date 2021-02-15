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

namespace BlueFormBuilder\Core\Block;

class Builder extends \Magezon\Builder\Block\Builder
{
	/**
	 * @param \Magento\Framework\View\Element\Template\Context          $context        
	 * @param \BlueFormBuilder\Core\Model\CompositeConfigProvider $configProvider 
	 * @param array                                                     $data           
	 */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \BlueFormBuilder\Core\Model\CompositeConfigProvider $configProvider,
        array $data = []
    ) {
        parent::__construct($context, $configProvider, $data);
    }
}