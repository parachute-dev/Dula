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

namespace BlueFormBuilder\Core\Cron;

class SendMail
{
    /**
     * @var \BlueFormBuilder\Core\Model\EmailNotificationFactory
     */
    protected $emailNotificationFactory;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory
     */
    protected $submissionCollectionFactory;

    /**
     * @param \BlueFormBuilder\Core\Model\EmailNotificationFactory                   $emailNotificationFactory    
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory 
     */
    public function __construct(
        \BlueFormBuilder\Core\Model\EmailNotificationFactory $emailNotificationFactory,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory
    ) {
        $this->emailNotificationFactory = $emailNotificationFactory;
        $this->submissionCollectionFactory = $submissionCollectionFactory;
    }

    /**
     * Delete un-usued attachments
     *
     * @return void
     */
    public function execute()
    {
        $ids        = [];
        $collection = $this->submissionCollectionFactory->create();
        foreach ($collection as $submission) {
            if ($submission->getAdminNotification() && !$submission->getSendCount()) {
                $emailNotification = $this->emailNotificationFactory->create();
                $emailNotification->setSubmission($submission)->sendAdminNotification();
                $emailNotification->updateSubmission();
            }
            if ($submission->getEnableCustomerNotification() && !$submission->getCustomerSendCount()) {
                $emailNotification = $this->emailNotificationFactory->create();
                $emailNotification->setSubmission($submission)->sendCustomerNotification();
                $emailNotification->updateSubmission();
            }
        }
    }
}
