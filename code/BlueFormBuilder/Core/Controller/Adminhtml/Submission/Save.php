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

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'BlueFormBuilder_Core::submission_save';

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context                   $context       
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor 
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $redirectBack = $this->getRequest()->getParam('back', false);
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if (empty($data['submission_id'])) {
            unset($data['submission_id']);
        }
        if ($data) {

            /** @var \BlueFormBuilder\Core\Model\Submission $model */
            $model = $this->_objectManager->create(\BlueFormBuilder\Core\Model\Submission::class);
            $id    = $this->getRequest()->getParam('submission_id');

            try {
                $model->load($id);
                if ($id && !$model->getId()) {
                    throw new LocalizedException(__('This submission no longer exists.'));
                }
                $model->addData($data);
                $model->save();

                $this->messageManager->addSuccessMessage(__('You saved the submission.'));
                $this->dataPersistor->clear('current_submission');

                return $resultRedirect->setPath('*/*/edit', ['submission_id' => $model->getId(), '_current' => true]);
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the submission.'));
            }

            $this->dataPersistor->set('current_submission', $data);
            return $resultRedirect->setPath('*/*/edit', ['submission_id' => $this->getRequest()->getParam('submission_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
