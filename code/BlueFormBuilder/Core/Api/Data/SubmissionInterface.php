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

interface SubmissionInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const FIELD_ID                       = 'submission_id';
    const FIELD_FORM_ID                  = 'form_id';
    const FIELD_INCREMENT_ID             = 'increment_id';
    const FIELD_CUSTOMER_ID              = 'customer_id';
    const FIELD_PRODUCT_ID               = 'product_id';
    const FIELD_REMOTE_IP                = 'remote_ip';
    const FIELD_REMOTE_IP_LONG           = 'remote_ip_long';
    const FIELD_SUBMISSION_CONTENT       = 'submission_content';
    const FIELD_ADMIN_SUBMISSION_CONTENT = 'admin_submission_content';
    const FIELD_VALUES                   = 'values';
    const FIELD_PROCESSED_PARAMS         = 'processed_params';
    const FIELD_ELEMENTS                 = 'elements';
    const FIELD_SUBMITTED_PAGE           = 'submitted_page';
    const FIELD_STORE_ID                 = 'store_id';
    const FIELD_BROWSER                  = 'browser';
    const FIELD_CREATION_TIME            = 'creation_time';
    const FIELD_UPDATE_TIME              = 'update_time';
    const FIELD_SENDER_NAME              = 'sender_name';
    const FIELD_SENDER_EMAIL             = 'sender_email';
    const FIELD_REPLY_TO                 = 'reply_to';
    const FIELD_RECIPIENTS               = 'recipients';
    const FIELD_RECIPIENTS_BCC           = 'recipients_bcc';
    const FIELD_EMAIL_SUBJECT            = 'email_subject';
    const FIELD_EMAIL_BODY               = 'email_body';
    const FIELD_CUSTOMER_SENDER_NAME     = 'customer_sender_name';
    const FIELD_CUSEOMER_SENDER_EMAIL    = 'customer_sender_email';
    const FIELD_CUSTOMER_REPLY_TO        = 'customer_reply_to';
    const FIELD_CUSTOMER_EMAIL_SUBJECT   = 'customer_email_subject';
    const FIELD_CUSTOMER_EMAIL_BODY      = 'customer_email_body';
    const FIELD_STATUS                   = 'status';
    const FIELD_READ                     = 'read';
    const FIELD_SUBMISSION_HASH          = 'submission_hash';
    const FIELD_CUSTOMER_RECIPIENTS      = 'customer_recipients';
    const FIELD_FORM_PARAMS              = 'form_params';
    const EXTENSION_ATTRIBUTES_KEY       = 'extension_attributes';
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
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setId($id);

    /**
     * Get Form ID
     *
     * @return int|null
     */
    public function getFormId();

    /**
     * Set Form ID
     *
     * @param int $formId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setFormId($formId);

    /**
     * Get increment id
     *
     * @return string
     */
    public function getIncrementId();

    /**
     * Set increment id
     *
     * @param string $incrementId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setIncrementId($incrementId);

    /**
     * Get Customer ID
     *
     * @return int|null
     */
    public function getCustomerId();

    /**
     * Set Customer ID
     *
     * @param int $customerId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerId($customerId);

    /**
     * Get Product ID
     *
     * @return int|null
     */
    public function getProductId();

    /**
     * Set Product ID
     *
     * @param int $productId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setProductId($productId);

    /**
     * Get remote ip
     *
     * @return string
     */
    public function getRemoteIp();

    /**
     * Set remote ip
     *
     * @param string $remoteIp
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRemoteIp($remoteIp);

    /**
     * Get remote ip long
     *
     * @return string
     */
    public function getRemoteIpLong();

    /**
     * Set remote ip long
     *
     * @param string $remoteIpLong
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRemoteIpLong($remoteIpLong);

    /**
     * Get submission content
     *
     * @return string|null
     */
    public function getSubmissionContent();

    /**
     * Set submission content
     *
     * @param string $submissionContent
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSubmissionContent($submissionContent);

    /**
     * Get admin submission content
     *
     * @return string|null
     */
    public function getAdminSubmissionContent();

    /**
     * Set admin submission content
     *
     * @param string $adminSubmissionContent
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setAdminSubmissionContent($adminSubmissionContent);

    /**
     * Get values
     *
     * @return string|null
     */
    public function getValues();

    /**
     * Set values
     *
     * @param string $values
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setValues($values);

    /**
     * Get processed params
     *
     * @return string|null
     */
    public function getProcessedParams();

    /**
     * Set processed params
     *
     * @param string $processedParams
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setProcessedParams($processedParams);

    /**
     * Get elements
     *
     * @return string|null
     */
    public function getElements();

    /**
     * Set elements
     *
     * @param string $elements
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setElements($elements);

    /**
     * Get submitted page
     *
     * @return string
     */
    public function getSubmittedPage();

    /**
     * Set submitted page
     *
     * @param string $submittedPage
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSubmittedPage($submittedPage);

    /**
     * Get store id
     *
     * @return string
     */
    public function getStoreId();

    /**
     * Set store id
     *
     * @param string $storeId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setStoreId($storeId);

    /**
     * Get browser
     *
     * @return string
     */
    public function getBrowser();

    /**
     * Set browser
     *
     * @param string $browser
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setBrowser($browser);

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime();

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCreationTime($creationTime);

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime();

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setUpdateTime($updateTime);

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
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSenderName($senderName);

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
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSenderEmail($senderEmail);

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
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setReplyTo($replyTo);

    /**
     * Get recipients
     *
     * @return string
     */
    public function getRecipients();

    /**
     * Set recipients
     *
     * @param string $recipients
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRecipients($recipients);

    /**
     * Get recipients bcc
     *
     * @return string
     */
    public function getRecipientsBcc();

    /**
     * Set recipients bcc
     *
     * @param string $recipientsBcc
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRecipientsBcc($recipientsBcc);

    /**
     * Get email subject
     *
     * @return string
     */
    public function getEmailSubject();

    /**
     * Set email subject
     *
     * @param string $emailSubject
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setEmailSubject($emailSubject);

    /**
     * Get email body
     *
     * @return string
     */
    public function getEmailBody();

    /**
     * Set email body
     *
     * @param string $emailBody
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setEmailBody($emailBody);

    /**
     * Get customer sender name
     *
     * @return string
     */
    public function getCustomerSenderName();

    /**
     * Set customer sender name
     *
     * @param string $customerSenderName
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerSenderName($customerSenderName);

    /**
     * Get customer sender email
     *
     * @return string
     */
    public function getCustomerSenderEmail();

    /**
     * Set customer sender email
     *
     * @param string $customerSenderEmail
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerSenderEmail($customerSenderEmail);

    /**
     * Get customer reply to
     *
     * @return string
     */
    public function getCustomerReplyTo();

    /**
     * Set customer reply to
     *
     * @param string $customerReplyTo
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerReplyTo($customerReplyTo);

    /**
     * Get customer email subject
     *
     * @return string
     */
    public function getCustomerEmailSubject();

    /**
     * Set customer email subject
     *
     * @param string $customerEmailSubject
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerEmailSubject($customerEmailSubject);

    /**
     * Get customer email body
     *
     * @return string
     */
    public function getCustomerEmailBody();

    /**
     * Set customer email body
     *
     * @param string $customerEmailBody
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerEmailBody($customerEmailBody);

    /**
     * Get read
     *
     * @return string
     */
    public function getRead();

    /**
     * Set read
     *
     * @param string $read
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRead($read);

    /**
     * Get submission hash
     *
     * @return string
     */
    public function getSubmissionHash();

    /**
     * Set submission hash
     *
     * @param string $submissionHash
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSubmissionHash($submissionHash);

    /**
     * Get customer recipients
     *
     * @return string
     */
    public function getCustomerRecipients();

    /**
     * Set customer recipients
     *
     * @param string $customerRecipients
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerRecipients($customerRecipients);

    /**
     * Get form params
     *
     * @return string
     */
    public function getFormParams();

    /**
     * Set form params
     *
     * @param string $formParams
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setFormParams($formParams);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \BlueFormBuilder\Core\Api\Data\SubmissionExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\BlueFormBuilder\Core\Api\Data\SubmissionExtensionInterface $extensionAttributes);
}
