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

namespace BlueFormBuilder\Core\Block\Adminhtml\Submission\Edit\Tab;

class AdminNotification extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \BlueFormBuilder\Core\Model\Submission */
        $model = $this->_coreRegistry->registry('current_submission');
        
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('form_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => '',
                'class'  => 'fieldset-wide'
            ]
        );

        if ($model->getId()) $fieldset->addField('submission_id', 'hidden', ['name' => 'submission_id']);

        $fieldset->addField(
            'sender_name',
            'note',
            [
                'label' => __('Sender Name'),
                'note'  =>  $model->getSenderName() . ' '
            ]
        );

        $fieldset->addField(
            'sender_email',
            'note',
            [
                'label' => __('Sender Email'),
                'note'  =>  $model->getSenderEmail() . ' '
            ]
        );

        $fieldset->addField(
            'reply_to',
            'note',
            [
                'label' => __('Reply To'),
                'note'  =>  $model->getReplyTo() . ' '
            ]
        );

        $fieldset->addField(
            'recipients',
            'note',
            [
                'label' => __('Send Email(s) To'),
                'note'  =>  $model->getRecipients() . ' '
            ]
        );

        $fieldset->addField(
            'bcc',
            'note',
            [
                'label' => __('BBC'),
                'note'  =>  $model->getRecipientsBcc() . ' '
            ]
        );

        $fieldset->addField(
            'customer_email_subject',
            'note',
            [
                'label' => __('Email Subject'),
                'note'  =>  $model->getEmailSubject() . ' '
            ]
        );

        $fieldset->addField(
            'customer_email_body',
            'note',
            [
                'label' => __('Email Body'),
                'note'  =>  $model->getEmailBody() . ' '
            ]
        );
        
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Admin Notification');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Admin Notification');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
