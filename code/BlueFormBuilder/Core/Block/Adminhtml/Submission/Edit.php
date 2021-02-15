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

namespace BlueFormBuilder\Core\Block\Adminhtml\Submission;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry           $registry
     * @param \Magento\Backend\Model\Auth\Session   $authSession
     * @param array                                 $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Backend\Model\Auth\Session $authSession,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->authSession   = $authSession;
        parent::__construct($context, $data);
    }

    protected function _construct()
    {
        $this->_objectId   = 'submission_id';
        $this->_blockGroup = 'BlueFormBuilder_Core';
        $this->_controller = 'adminhtml_submission';

        parent::_construct();

        if (!$this->_isAllowedAction('BlueFormBuilder_Core::submission_delete')) {
            $this->buttonList->remove('delete');
        }

        $submission = $this->getSubmission();

        $this->addButton(
            'markasunread',
            [
                'label'   => __('Mark as unread'),
                'onclick' => 'setLocation(\'' . $this->getMarkUnread() . '\')'
            ],
            -1
        );

        $this->addButton(
            'view',
            [
                'label'   => __('View'),
                'onclick' => 'setLocation(\'' . $submission->getTrackLink() . '\')'
            ],
            1
        );

        if ($this->_isAllowedAction('BlueFormBuilder_Core::submission_save')) {
            $this->addButton(
                'edit',
                [
                    'label'   => __('Edit'),
                    'onclick' => 'setLocation(\'' . $this->getEditUrl() . '\')',
                    'class'   => 'action-secondary'
                ],
                1
            );
        }

        if ($submission->getSenderEmail()) {
            $message = __('Are you sure you want to send an email to admin?');
            $this->addButton(
                'send_admin_notification',
                [
                    'label'   => __('Send Admin Email'),
                    'class'   => 'send-admin-email',
                    'onclick' => "confirmSetLocation('{$message}', '{$this->getAdminEmailUrl()}')"
                ]
            );
        }

        if ($submission->getCustomerSenderEmail()) {
            $message = __('Are you sure you want to send an email to customer?');
            $this->addButton(
                'send_customer_notification',
                [
                    'label'   => __('Send Customer Email'),
                    'class'   => 'send-customer-email',
                    'onclick' => "confirmSetLocation('{$message}', '{$this->getCustomerEmailUrl()}')"
                ]
            );
        }

        $this->buttonList->remove('reset');
    }

    /**
     * @return BlueFormBuilder\Core\Model\Submission
     */
    protected function getSubmission()
    {
        return $this->_coreRegistry->registry('current_submission');
    }

    /**
     * @return string
     */
    protected function getEditUrl()
    {
        $submission = $this->getSubmission();
        $form       = $submission->getForm();
        $url        = $form->getUrl();
        $url        .= '?submission=' . $submission->getSubmissionHash();
        $url        .= '&key=' . $this->authSession->getSessionId();
        return $url;
    }

    /**
     * @return string
     */
    protected function getMarkUnread()
    {
        return $this->getUrl('*/*/markUnread', ['_current' => true]);
    }

    /**
     * @return string
     */
    protected function getExportCsv()
    {
        return $this->getUrl('*/*/exportCsv', ['_current' => true]);
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * Email URL getter
     *
     * @return string
     */
    public function getAdminEmailUrl()
    {
        return $this->getUrl('*/*/adminEmail', ['submission_id' => $this->getSubmission()->getId()]);
    }

    /**
     * Email URL getter
     *
     * @return string
     */
    public function getCustomerEmailUrl()
    {
        return $this->getUrl('*/*/customerEmail', ['submission_id' => $this->getSubmission()->getId()]);
    }

}
