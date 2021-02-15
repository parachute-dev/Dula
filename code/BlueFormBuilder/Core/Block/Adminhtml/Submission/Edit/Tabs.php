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

namespace BlueFormBuilder\Core\Block\Adminhtml\Submission\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\Block\Template\Context  $context
     * @param \Magento\Framework\Json\EncoderInterface $jsonEncoder
     * @param \Magento\Backend\Model\Auth\Session      $authSession
     * @param \Magento\Framework\Registry              $registry
     * @param array                                    $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        parent::__construct($context, $jsonEncoder, $authSession);
        $this->_coreRegistry = $registry;
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('submission_tabs');
        $this->setDestElementId('edit_form');
    }

    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $submission     = $this->_coreRegistry->registry('current_submission');
        $submissionData = str_replace('blueformbuilder/file/download', 'blueformbuilder/file/download/backend/1', $submission->getAdminSubmissionContent());

        $this->addTab(
            'main_message',
            [
                'label'   => __('Submission Data'),
                'title'   => __('Submission Data'),
                'content' => $submissionData
            ]
        );

        if ($submission->getCustomerEmailBody() && $submission->getCustomerRecipients()) {
            $this->addTab(
                'customer_notification',
                [
                    'label'   => __('Customer Notification'),
                    'title'   => __('Customer Notification'),
                    'content' => $this->getLayout()->createBlock('BlueFormBuilder\Core\Block\Adminhtml\Submission\Edit\Tab\CustomerNotification')->toHtml()
                ]
            );
        }

        if ($submission->getEmailBody() && $submission->getRecipients()) {
            $this->addTab(
                'admin_notification',
                [
                    'label'   => __('Admin Notification'),
                    'title'   => __('Admin Notification'),
                    'content' => $this->getLayout()->createBlock('BlueFormBuilder\Core\Block\Adminhtml\Submission\Edit\Tab\AdminNotification')->toHtml()
                ]
            );
        }

        $this->addTab(
            'info',
            [
                'label'   => __('More Informations'),
                'title'   => __('More Informations'),
                'content' => $this->getLayout()->createBlock('BlueFormBuilder\Core\Block\Adminhtml\Submission\Edit\Tab\Info')->toHtml()
            ]
        );

        $this->addTab(
            'advanced',
            [
                'label'   => __('Advanced'),
                'title'   => __('Advanced'),
                'content' => $this->getLayout()->createBlock('BlueFormBuilder\Core\Block\Adminhtml\Submission\Edit\Tab\Advanced')->toHtml()
            ]
        );

        return parent::_beforeToHtml();
    }
}
