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

use Magento\Framework\Exception\LocalizedException;

class AdminEmail extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'BlueFormBuilder_Core::submission_save';

    /**
     * @var \BlueFormBuilder\Core\Model\EmailNotification
     */
    protected $emailNotification;

    /**
     * @param \Magento\Backend\App\Action\Context           $context           
     * @param \BlueFormBuilder\Core\Model\EmailNotification $emailNotification 
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \BlueFormBuilder\Core\Model\EmailNotification $emailNotification
    ) {
        parent::__construct($context);
        $this->emailNotification = $emailNotification;
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('submission_id');
        $redirectBack = $this->getRequest()->getParam('back', false);
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {

            /** @var \BlueFormBuilder\Core\Model\Submission $model */
            $model = $this->_objectManager->create(\BlueFormBuilder\Core\Model\Submission::class);

            try {
                $model->load($id);
                if ($id && !$model->getId()) {
                    throw new LocalizedException(__('This submission no longer exists.'));
                }
                $this->emailNotification->setSubmission($model)->sendAdminNotification();
                $this->messageManager->addSuccessMessage(__('You sent the email to admin.'));
                return $resultRedirect->setPath('*/*/edit', ['submission_id' => $model->getId(), '_current' => true]);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('We can\'t send the email right now.'));
            }

            return $resultRedirect->setPath('*/*/edit', ['submission_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
