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

namespace BlueFormBuilder\Core\Api\Data;

interface FormInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const FIELD_FORM_ID                = 'form_id';
    const FIELD_NAME                   = 'name';
    const FIELD_IDENTIFIER             = 'identifier';
    const FIELD_REDIRECT_TO            = 'redirect_to';
    const FIELD_SHOW_TOPLINK           = 'show_toplink';
    const FIELD_SUCCESS_MESSAGE        = 'success_message';
    const FIELD_RECIPIENTS             = 'recipients';
    const FIELD_RECIPIENTS_BBC         = 'recipients_bcc';
    const FIELD_EMAIL_SUBJECT          = 'email_subject';
    const FIELD_EMAIL_BODY             = 'email_body';
    const FIELD_CUSTOMER_SENDER_NAME   = 'customer_sender_name';
    const FIELD_CUSTOMER_SENDER_EMAIL  = 'customer_sender_email';
    const FIELD_CUSTOMER_EMAIL_SUBJECT = 'customer_email_subject';
    const FIELD_CUSTOMER_EMAIL_BODY    = 'customer_email_body';
    const FIELD_META_TITLE             = 'meta_title';
    const FIELD_META_DESCRIPTION       = 'meta_description';
    const FIELD_META_KEYWORDS          = 'meta_keywords';
    const FIELD_IS_ACTIVE              = 'is_active';
    const FIELD_CREATION_TIME          = 'creation_time';
    const FIELD_UPDATED_TIME           = 'update_time';
    const FIELD_MULTIPLE_PAGE          = 'multiple_page';
    const FIELD_REDIRECT_DELAY         = 'redirect_delay';
    const FIELD_SUBMISSION_PREFIX      = 'submission_prefix';
    const FIELD_ATTACH_FILES           = 'attach_files';
    const FIELD_CUSTOMER_REPLY_TO      = 'customer_reply_to';
    const FIELD_REPLY_TO               = 'reply_to';
    const FIELD_SENDER_EMAIL           = 'sender_email';
    const FIELD_SENDER_NAME            = 'sender_name';
    const FIELD_PAGE_LAYOUT            = 'page_layout';
    const FIELD_CUSTOM_CLASSES         = 'custom_classes';
    const FIELD_CUSTOM_CSS             = 'custom_css';
    const EXTENSION_ATTRIBUTES_KEY     = 'extension_attributes';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setId($id);

    /**
     * Get form name
     *
     * @return string
     */
    public function getName();

    /**
     * Set form name
     *
     * @param string $name
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setName($name);

    /**
     * Get form identifier
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Set form identifier
     *
     * @param string $identifier
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setIdentifier($identifier);

    /**
     * Get form redirect to
     *
     * @return string
     */
    public function getRedirectTo();

    /**
     * Set form redirect to
     *
     * @param string $redirectTo
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setRedirectTo($redirectTo);

    /**
     * Show in toplink
     *
     * @return bool|null
     */
    public function getShowToplink();

    /**
     * Set show in toplinks
     *
     * @param int|bool $showToplink
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setShowToplink($showToplink);

    /**
     * Get success message
     *
     * @return string|null
     */
    public function getSuccessMessage();

    /**
     * Set success message
     *
     * @param string $successMessage
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setSuccessMessage($successMessage);

    /**
     * Get recipients
     *
     * @return string|null
     */
    public function getRecipients();

    /**
     * Set recipients
     *
     * @param string $recipients
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setRecipients($recipients);

    /**
     * Get recipients bcss
     *
     * @return string|null
     */
    public function getRecipientsBcc();

    /**
     * Set recipients bcc
     *
     * @param string $recipientsBcc
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setRecipientsBcc($recipientsBcc);

    /**
     * Get email subject
     *
     * @return string|null
     */
    public function getEmailSubject();

    /**
     * Set email subject
     *
     * @param string $emailSubject
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setEmailSubject($emailSubject);

    /**
     * Get email body
     *
     * @return string|null
     */
    public function getEmailBody();

    /**
     * Set email body
     *
     * @param string $emailBody
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setEmailBody($emailBody);

    /**
     * Get customer sender name
     *
     * @return string|null
     */
    public function getCustomerSenderName();

    /**
     * Set customer sender name
     *
     * @param string $customerSenderName
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerSenderName($customerSenderName);

    /**
     * Get customer sender email
     *
     * @return string|null
     */
    public function getCustomerSenderEmail();

    /**
     * Set customer sender email
     *
     * @param string $customerSenderEmail
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerSenderEmail($customerSenderEmail);

    /**
     * Get customer email subject
     *
     * @return string|null
     */
    public function getCustomerEmailSubject();

    /**
     * Set customer email subject
     *
     * @param string $customerEmailSubject
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerEmailSubject($customerEmailSubject);

    /**
     * Get customer email body
     *
     * @return string|null
     */
    public function getCustomerEmailBody();

    /**
     * Set customer email body
     *
     * @param string $customerEmailSubject
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerEmailBody($customerEmailBody);

    /**
     * Get meta title
     *
     * @return string|null
     */
    public function getMetaTitle();

    /**
     * Set meta title
     *
     * @param string $metaTitle
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setMetaTitle($metaTitle);

    /**
     * Get meta description
     *
     * @return string|null
     */
    public function getMetaDescription();

    /**
     * Set meta title
     *
     * @param string $metaDescription
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setMetaDescription($metaDescription);

    /**
     * Get meta keywords
     *
     * @return string|null
     */
    public function getMetaKeywords();

    /**
     * Set meta title
     *
     * @param string $metaKeywords
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setMetaKeywords($metaKeywords);

    /**
     * Is active
     *
     * @return bool|null
     */
    public function getIsActive();

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setIsActive($isActive);

    /**
     * @return string|null
     */
    public function getCreationTime();

    /**
     * @param string $creationTime
     * @return $this
     */
    public function setCreationTime($creationTime);

    /**
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * @param string $updateTime
     * @return $this
     */
    public function setUpdateTime($updateTime);

    /**
     * @return bool|null
     */
    public function getMultiplePage();

    /**
     * @param int|bool $isMultiplePage
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setMultiplePage($isMultiplePage);

    /**
     * Get form redirect delay
     *
     * @return int
     */
    public function getRedirectDelay();

    /**
     * Set form redirect delay
     *
     * @param int $redirectDelay
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setRedirectDelay($redirectDelay);

    /**
     * Get submission prefix
     *
     * @return string
     */
    public function getSubmissionPrefix();

    /**
     * Set submission prefix
     *
     * @param string $submissionPrefix
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setSubmissionPrefix($submissionPrefix);

    /**
     * Get attach files
     *
     * @return bool
     */
    public function getAttachFiles();

    /**
     * Set attach files
     *
     * @param bool $attachFiles
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setAttachFiles($attachFiles);

    /**
     * Get customer reply to
     *
     * @return string
     */
    public function getCustomerReplyTo();

    /**
     * Set attach files
     *
     * @param string $customerReplyTo
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerReplyTo($customerReplyTo);

    /**
     * Get reply to
     *
     * @return string
     */
    public function getReplyTo();

    /**
     * Set reply to
     *
     * @param string $replyTo
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setReplyTo($replyTo);

    /**
     * Get sender email
     *
     * @return string
     */
    public function getSenderEmail();

    /**
     * Set sender email
     *
     * @param string $senderEmail
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setSenderEmail($senderEmail);

    /**
     * Get sender name
     *
     * @return string
     */
    public function getSenderName();

    /**
     * Set sender name
     *
     * @param string $senderName
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setSenderName($senderName);

    /**
     * Get page layout
     *
     * @return string
     */
    public function getPageLayout();

    /**
     * Set page layout
     *
     * @param string $pageLayout
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setPageLayout($pageLayout);

    /**
     * Get custom classes
     *
     * @return string
     */
    public function getCustomClasses();

    /**
     * Set custom classes
     *
     * @param string $customClasses
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomClasses($customClasses);

    /**
     * Get custom css
     *
     * @return string
     */
    public function getCustomCss();

    /**
     * Set custom css
     *
     * @param string $customCss
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomCss($customCss);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \BlueFormBuilder\Core\Api\Data\FormExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \BlueFormBuilder\Core\Api\Data\FormExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\BlueFormBuilder\Core\Api\Data\FormExtensionInterface $extensionAttributes);
}
