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

namespace BlueFormBuilder\Core\Block\Adminhtml\Form\Submission\Field\Renderer;

class Action extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
	/**
	 * @var \Magento\Framework\Url
	 */
	protected $_urlBuilder;

	/**
	 * @param \Magento\Backend\Block\Context $context    
	 * @param \Magento\Framework\Url         $urlBuilder 
	 */
	public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Framework\Url $urlBuilder
   	) {
		$this->_urlBuilder = $urlBuilder;
        parent::__construct($context);
	}

	public function _getValue(\Magento\Framework\DataObject $row) {
		$editUrl = $this->_urlBuilder->getUrl(
            'blueformbuilder/submission/edit',
            [
				'submission_id' => $row->getData('submission_id')
            ]
        );
		return sprintf("<a href='%s'>View</a>", $editUrl);
	}
}