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

namespace BlueFormBuilder\Core\Block\Adminhtml\Form;

class SubmissionGridContainer extends \Magento\Backend\Block\Template
{
    /**
     * Path to template file in theme.
     *
     * @var string
     */
	protected $_template = 'form/submission_grid_container.phtml';

	/**
	 * @var \Magento\Framework\Registry
	 */
	protected $registry;

	/**
	 * @param \Magento\Backend\Block\Template\Context $context  
	 * @param \Magento\Framework\Registry             $registry 
	 * @param array                                   $data     
	 */
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
	) {
		parent::__construct($context, $data);
		$this->registry = $registry;
	}

	/**
	 * @return \BlueFormBuilder\Core\Model\Form
	 */
	public function getCurrentForm()
	{
		return $this->registry->registry('current_form');
	}

	public function getGridBlock()
	{
		return $this->getLayout()->createBlock(\BlueFormBuilder\Core\Block\Adminhtml\Form\SubmissionGrid::class);
	}
}