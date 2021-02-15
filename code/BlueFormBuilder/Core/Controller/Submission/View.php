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

namespace BlueFormBuilder\Core\Controller\Submission;

use Magento\Framework\Controller\ResultFactory;

class View extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Framework\App\Action\Context               $context
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\Registry                         $coreRegistry
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Registry $coreRegistry
    ) {
        parent::__construct($context);
        $this->resultForwardFactory = $resultForwardFactory;
        $this->_coreRegistry        = $coreRegistry;
    }

    /**
     * Form view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $submission = $this->_coreRegistry->registry('current_submission');
        if (!$submission || !$submission->getId()) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }

        $resultPage->addPageLayoutHandles(['id' => $submission->getId()]);

        return $resultPage;
    }
}
