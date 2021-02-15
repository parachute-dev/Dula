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

namespace BlueFormBuilder\Core\Controller\Adminhtml\Submission;

class Delete extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'BlueFormBuilder_Core::submission_delete';

    /**
     * @var \BlueFormBuilder\Core\Api\FormRepositoryInterface
     */
    protected $submissionRepository;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \BlueFormBuilder\Core\Api\FormRepositoryInterface $submissionRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \BlueFormBuilder\Core\Api\SubmissionRepositoryInterface $submissionRepository
    ) {
        parent::__construct($context);
        $this->submissionRepository = $submissionRepository;
    }

    /**
     * Delete submission action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $submissionId   = $this->getRequest()->getParam('submission_id');
        if ($submissionId) {
            try {
                $submission = $this->submissionRepository->get($submissionId);
                $this->submissionRepository->delete($submission);
                $this->messageManager->addSuccess(__('You deleted the submission.'));
                $this->_eventManager->dispatch('blueformbuilder_controller_submission_delete', ['submission' => $submission, 'status' => 'success']);
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch('blueformbuilder_controller_submission_delete', ['status' => 'fail']);
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/');
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a submission to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
