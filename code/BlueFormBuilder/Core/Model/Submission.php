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

namespace BlueFormBuilder\Core\Model;

use BlueFormBuilder\Core\Api\Data\SubmissionInterface;
use Magento\Framework\Model\AbstractModel;

class Submission extends AbstractModel implements SubmissionInterface
{
    const INCREMENT_PAD_LENGTH = 8;
    const INCREMENT_PAD_PREFIX = 'BFB';

    /**#@+
     * Page's Statuses
     */
    const STATUS_READ   = 1;
    const STATUS_UNREAD = 0;

    /**
     * Submision page cache tag
     */
    const CACHE_TAG = 'blueformbuilder_submission';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'blueformbuilder_submission';

    /**
     * @var \BlueFormBuilder\Core\Model\FormFactory
     */
    protected $_formFactory;

    /**
     * @var \BlueFormBuilder\Core\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    /**
     * @var BlueFormBuilder\Core\Model\ResourceModel\File\Collection
     */
    protected $fileCollection;
    protected $_form;

    protected $cache = [];

    /**
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \BlueFormBuilder\Core\Helper\Data                            $dataHelper
     * @param \BlueFormBuilder\Core\Model\FormFactory                      $formFactory
     * @param \Magento\Store\Model\StoreManagerInterface                   $storeManager
     * @param \Magento\Customer\Model\CustomerFactory                      $customerFactory
     * @param \Magento\Catalog\Model\ProductFactory                        $productFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \BlueFormBuilder\Core\Helper\Data $dataHelper,
        \BlueFormBuilder\Core\Model\FormFactory $formFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magezon\Core\Helper\Data $coreHelper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->dataHelper      = $dataHelper;
        $this->_formFactory    = $formFactory;
        $this->_storeManager   = $storeManager;
        $this->customerFactory = $customerFactory;
        $this->productFactory  = $productFactory;
        $this->coreHelper      = $coreHelper;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\BlueFormBuilder\Core\Model\ResourceModel\Submission::class);
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return parent::getData(self::FIELD_ID);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setId($id)
    {
        return $this->setData(self::FIELD_ID, $id);
    }

    /**
     * Get Form ID
     *
     * @return int|null
     */
    public function getFormId()
    {
        return parent::getData(self::FIELD_FORM_ID);
    }

    /**
     * Set Form ID
     *
     * @param int $formId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setFormId($formId)
    {
        return $this->setData(self::FIELD_FORM_ID, $formId);
    }

    /**
     * Get increment id
     *
     * @return string
     */
    public function getIncrementId()
    {
        return parent::getData(self::FIELD_INCREMENT_ID);
    }

    /**
     * Set increment id
     *
     * @param string $incrementId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setIncrementId($incrementId)
    {
        return $this->setData(self::FIELD_INCREMENT_ID, $incrementId);
    }

    /**
     * Get Customer ID
     *
     * @return int|null
     */
    public function getCustomerId()
    {
        return parent::getData(self::FIELD_CUSTOMER_ID);
    }

    /**
     * Set Customer ID
     *
     * @param int $customerId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerId($customerId)
    {
        return $this->setData(self::FIELD_CUSTOMER_ID, $customerId);
    }

    /**
     * Get Product ID
     *
     * @return int|null
     */
    public function getProductId()
    {
        return parent::getData(self::FIELD_PRODUCT_ID);
    }

    /**
     * Set Product ID
     *
     * @param int $productId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setProductId($productId)
    {
        return $this->setData(self::FIELD_PRODUCT_ID, $productId);
    }

    /**
     * Get remote ip
     *
     * @return string
     */
    public function getRemoteIp()
    {
        return parent::getData(self::FIELD_REMOTE_IP);
    }

    /**
     * Set remote ip
     *
     * @param string $remoteIp
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRemoteIp($remoteIp)
    {
        return $this->setData(self::FIELD_REMOTE_IP, $remoteIp);
    }

    /**
     * Get remote ip long
     *
     * @return string
     */
    public function getRemoteIpLong()
    {
        return parent::getData(self::FIELD_REMOTE_IP_LONG);
    }

    /**
     * Set remote ip long
     *
     * @param string $remoteIpLong
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRemoteIpLong($remoteIpLong)
    {
        return $this->setData(self::FIELD_REMOTE_IP_LONG, $remoteIpLong);
    }

    /**
     * Get content
     *
     * @return string|null
     */
    public function getSubmissionContent()
    {
        return parent::getData(self::FIELD_ADMIN_SUBMISSION_CONTENT);
    }

    /**
     * Set submission content
     *
     * @param string $submissionContent
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSubmissionContent($submissionContent)
    {
        return $this->setData(self::FIELD_ADMIN_SUBMISSION_CONTENT, $submissionContent);
    }

    /**
     * Get admin submission content
     *
     * @return string|null
     */
    public function getAdminSubmissionContent()
    {
        return parent::getData(self::FIELD_SUBMISSION_CONTENT);
    }

    /**
     * Set admin submission content
     *
     * @param string $adminSubmissionContent
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setAdminSubmissionContent($adminSubmissionContent)
    {
        return $this->setData(self::FIELD_SUBMISSION_CONTENT, $submissionContent);
    }

    /**
     * Get values
     *
     * @return string|null
     */
    public function getValues()
    {
        return parent::getData(self::FIELD_VALUES);
    }

    /**
     * Set values
     *
     * @param string $values
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setValues($values)
    {
        return $this->setData(self::FIELD_VALUES, $values);
    }

    /**
     * Get processed params
     *
     * @return string|null
     */
    public function getProcessedParams()
    {
        return parent::getData(self::FIELD_PROCESSED_PARAMS);
    }

    /**
     * Set processed params
     *
     * @param string $processedParams
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setProcessedParams($processedParams)
    {
        return $this->setData(self::FIELD_PROCESSED_PARAMS, $processedParams);
    }

    /**
     * Get elements
     *
     * @return string|null
     */
    public function getElements()
    {
        return parent::getData(self::FIELD_ELEMENTS);
    }

    /**
     * Set elements
     *
     * @param string $elements
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setElements($elements)
    {
        return $this->setData(self::FIELD_ELEMENTS, $elements);
    }

    /**
     * Get submitted page
     *
     * @return string
     */
    public function getSubmittedPage()
    {
        return parent::getData(self::FIELD_SUBMITTED_PAGE);
    }

    /**
     * Set submitted page
     *
     * @param string $submittedPage
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSubmittedPage($submittedPage)
    {
        return $this->setData(self::FIELD_SUBMITTED_PAGE, $submittedPage);
    }

    /**
     * Get store id
     *
     * @return string
     */
    public function getStoreId()
    {
        return parent::getData(self::FIELD_STORE_ID);
    }

    /**
     * Set store id
     *
     * @param string $storeId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::FIELD_STORE_ID, $storeId);
    }


    /**
     * Get browser
     *
     * @return string
     */
    public function getBrowser()
    {
        return parent::getData(self::FIELD_BROWSER);
    }

    /**
     * Set browser
     *
     * @param string $browser
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setBrowser($browser)
    {
        return $this->setData(self::FIELD_BROWSER, $browser);
    }

    /**
     * Get creation time
     *
     * @return string|null
     */
    public function getCreationTime()
    {
        return parent::getData(self::FIELD_CREATION_TIME);
    }

    /**
     * Set creation time
     *
     * @param string $creationTime
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::FIELD_CREATION_TIME, $creationTime);
    }

    /**
     * Get update time
     *
     * @return string|null
     */
    public function getUpdateTime()
    {
        return parent::getData(self::FIELD_UPDATE_TIME);
    }

    /**
     * Get sender name
     *
     * @return string
     */
    public function getSenderName()
    {
        return parent::getData(self::FIELD_SENDER_NAME);
    }

    /**
     * Set sender name
     *
     * @param string $senderName
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSenderName($senderName)
    {
        return $this->setData(self::FIELD_SENDER_NAME, $senderName);
    }

    /**
     * Get sender email
     *
     * @return string
     */
    public function getSenderEmail()
    {
        return parent::getData(self::FIELD_SENDER_EMAIL);
    }

    /**
     * Set sender email
     *
     * @param string $senderEmail
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSenderEmail($senderEmail)
    {
        return $this->setData(self::FIELD_SENDER_EMAIL, $senderEmail);
    }

    /**
     * Get reply to
     *
     * @return string
     */
    public function getReplyTo()
    {
        return parent::getData(self::FIELD_REPLY_TO);
    }

    /**
     * Set reply to
     *
     * @param string $replyTo
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setReplyTo($replyTo)
    {
        return $this->setData(self::FIELD_REPLY_TO, $replyTo);
    }

    /**
     * Get recipients
     *
     * @return string
     */
    public function getRecipients()
    {
        return parent::getData(self::FIELD_RECIPIENTS);
    }

    /**
     * Set recipients
     *
     * @param string $recipients
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRecipients($recipients)
    {
        return $this->setData(self::FIELD_RECIPIENTS, $recipients);
    }

    /**
     * Get recipients bcc
     *
     * @return string
     */
    public function getRecipientsBcc()
    {
        return parent::getData(self::FIELD_RECIPIENTS_BCC);
    }

    /**
     * Set recipients bcc
     *
     * @param string $recipientsBcc
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRecipientsBcc($recipientsBcc)
    {
        return $this->setData(self::FIELD_RECIPIENTS_BCC, $recipientsBcc);
    }

    /**
     * Get email subject
     *
     * @return string
     */
    public function getEmailSubject()
    {
        return parent::getData(self::FIELD_EMAIL_SUBJECT);
    }

    /**
     * Set email subject
     *
     * @param string $emailSubject
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setEmailSubject($emailSubject)
    {
        return $this->setData(self::FIELD_EMAIL_SUBJECT, $emailSubject);
    }

    /**
     * Get email body
     *
     * @return string
     */
    public function getEmailBody()
    {
        return parent::getData(self::FIELD_EMAIL_BODY);
    }

    /**
     * Set email body
     *
     * @param string $emailBody
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setEmailBody($emailBody)
    {
        return $this->setData(self::FIELD_EMAIL_BODY, $emailBody);
    }

    /**
     * Get customer sender name
     *
     * @return string
     */
    public function getCustomerSenderName()
    {
        return parent::getData(self::FIELD_CUSTOMER_SENDER_NAME);
    }

    /**
     * Set customer sender name
     *
     * @param string $customerSenderName
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerSenderName($customerSenderName)
    {
        return $this->setData(self::FIELD_CUSTOMER_SENDER_NAME, $customerSenderName);
    }

    /**
     * Get customer sender email
     *
     * @return string
     */
    public function getCustomerSenderEmail()
    {
        return parent::getData(self::FIELD_CUSEOMER_SENDER_EMAIL);
    }

    /**
     * Set customer sender email
     *
     * @param string $customerSenderEmail
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerSenderEmail($customerSenderEmail)
    {
        return $this->setData(self::FIELD_CUSEOMER_SENDER_EMAIL, $customerSenderEmail);
    }

    /**
     * Get customer reply to
     *
     * @return string
     */
    public function getCustomerReplyTo()
    {
        return parent::getData(self::FIELD_CUSTOMER_REPLY_TO);
    }

    /**
     * Set customer reply to
     *
     * @param string $customerReplyTo
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerReplyTo($customerReplyTo)
    {
        return $this->setData(self::FIELD_CUSTOMER_REPLY_TO, $customerReplyTo);
    }

    /**
     * Get customer email subject
     *
     * @return string
     */
    public function getCustomerEmailSubject()
    {
        return parent::getData(self::FIELD_CUSTOMER_EMAIL_SUBJECT);
    }

    /**
     * Set customer email subject
     *
     * @param string $customerEmailSubject
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerEmailSubject($customerEmailSubject)
    {
        return $this->setData(self::FIELD_CUSTOMER_EMAIL_SUBJECT, $customerEmailSubject);
    }

    /**
     * Get customer email body
     *
     * @return string
     */
    public function getCustomerEmailBody()
    {
        return parent::getData(self::FIELD_CUSTOMER_EMAIL_BODY);
    }

    /**
     * Set customer email body
     *
     * @param string $customerEmailBody
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerEmailBody($customerEmailBody)
    {
        return $this->setData(self::FIELD_CUSTOMER_EMAIL_BODY, $customerEmailBody);
    }

    /**
     * Set update time
     *
     * @param string $updateTime
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::FIELD_UPDATE_TIME, $updateTime);
    }

    /**
     * Get read
     *
     * @return string
     */
    public function getRead()
    {
        return parent::getData(self::FIELD_READ);
    }

    /**
     * Set read
     *
     * @param string $read
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setRead($read)
    {
        return $this->setData(self::FIELD_READ, $read);
    }

    /**
     * Get submission hash
     *
     * @return string
     */
    public function getSubmissionHash()
    {
        return parent::getData(self::FIELD_SUBMISSION_HASH);
    }

    /**
     * Set submission hash
     *
     * @param string $submissionHash
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setSubmissionHash($submissionHash)
    {
        return $this->setData(self::FIELD_SUBMISSION_HASH, $submissionHash);
    }

    /**
     * Get customer recipients
     *
     * @return string
     */
    public function getCustomerRecipients()
    {
        return parent::getData(self::FIELD_CUSTOMER_RECIPIENTS);
    }

    /**
     * Set customer recipients
     *
     * @param string $customerRecipients
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     */
    public function setCustomerRecipients($customerRecipients)
    {
        return $this->setData(self::FIELD_CUSTOMER_RECIPIENTS, $customerRecipients);
    }

    /**
     * Get form params
     *
     * @return string
     */
    public function getFormParams()
    {
        return parent::getData(self::FIELD_FORM_PARAMS);
    }

    /**
     * Set form params
     *
     * @param string $formParams
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setFormParams($formParams)
    {
        return $this->setData(self::FIELD_FORM_PARAMS, $formParams);
    }

    /**
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getForm()
    {
        if (!$this->hasData('form')) {
            $form = $this->_formFactory->create();
            $form->load($this->getFormId());
            $this->setForm($form);
        }
        return $this->getData('form');
    }

    /**
     * Retrieve store model instance
     *
     * @return \Magento\Store\Model\Store
     */
    public function getStore()
    {
        $storeId = $this->getStoreId();
        if ($storeId) {
            return $this->_storeManager->getStore($storeId);
        }
        return $this->_storeManager->getStore();
    }

    /**
     * @return \Magento\Customer\Model\Customer
     */
    public function getCustomer()
    {
        $customer = $this->customerFactory->create();
        $customer->load($this->getCustomerId());
        return $customer;
    }

    /**
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        $product = $this->productFactory->create();
        $product->load($this->getProductId());
        return $product;
    }

    /**
     * Get formatted form created date in store timezone
     *
     * @return  string
     */
    public function getCreatedAtFormatted()
    {
        $date            = new \DateTime($this->getCreationTime());
        $dateFieldsOrder = str_replace([',', 'y'], ['-', 'Y'], $this->dataHelper->getConfig('general/submittedat_date_fields_order'));
        return $date->format($dateFieldsOrder .' H:i:s');
    }

    /**
     * {@inheritdoc}
     *
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * {@inheritdoc}
     *
     * @param \BlueFormBuilder\Core\Api\Data\SubmissionExtensionInterface
     * @return $this
     */
    public function setExtensionAttributes(\BlueFormBuilder\Core\Api\Data\SubmissionExtensionInterface $extensionAttributes)
    {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

    /**
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [
            self::STATUS_READ   => __('Read'),
            self::STATUS_UNREAD => __('Unread')
        ];
    }

    /**
     * Retrieve submission file collection
     *
     * @return \BlueFormBuilder\Core\Model\ResourceModel\File\Collection
     */
    public function getFileCollection()
    {
        if ($this->fileCollection === null) {
            $fileCollection   = $this->_getResource()->getFileCollection($this->getId());
            $this->setFileCollection($fileCollection);
        }
        return $this->fileCollection;
    }

    /**
     * Set submission file collection
     *
     * @param \BlueFormBuilder\Core\Model\ResourceModel\File\Collection
     * @return $this
     */
    public function setFileCollection(\Magento\Framework\Data\Collection $fileCollection)
    {
        $this->fileCollection = $fileCollection;
        return $this;
    }

    /**
     * @return array
     */
    public function getSimpleValues()
    {
        $result = [];
        $values = $this->coreHelper->unserialize($this->getValues());
        if ($values && is_array($values)) {
            foreach ($values as $elemName => $value) {
                $result[$elemName] = $value['simple'];
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getHtmlValues()
    {
        $result = [];
        $values = $this->coreHelper->unserialize($this->getValues());
        if ($values && is_array($values)) {
            foreach ($values as $elemName => $value) {
                $result[$elemName] = $value['html'];
            }
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getEmailHtmlValues()
    {
        $result = [];
        $values = $this->coreHelper->unserialize($this->getValues());
        if ($values && is_array($values)) {
            foreach ($values as $elemName => $value) {
                $result[$elemName] = $value['email'];
            }
        }
        return $result;
    }

    /**
     * Get full form url
     *
     * @return string
     */
    public function getTrackLink()
    {
        $url   = $this->getStore()->getBaseUrl();
        return $url . 'submission-confirmed/' . $this->getSubmissionHash();
    }

    public function getVariables($htmlValues = true)
    {
        if (($htmlValues && !isset($this->cache[2])) || (!$htmlValues && !isset($this->cache[1]))) {
            $form = $this->getForm();
            $variables['submission_id']      = $this->getIncrementId();
            $variables['submission_date']    = $this->getCreatedAtFormatted();
            $variables['submission_content'] = $this->getAdminSubmissionContent();
            $variables['submit_from_page']   = $this->getSubmittedPage();
            $variables['form_name']          = $form->getName();
            $variables['form_id']            = $form->getId();
            $variables['form_url']           = $this->dataHelper->getFormUrl($form->getIdentifier());
            $variables['markunread_url']     = $this->dataHelper->getMarkUnreadUrl($this);
            $variables['visitor_ip']         = $this->getRemoteIp();
            $variables['submission_count']   = $form->getSubmissionCount();
            $variables['track_link']         = $this->getTrackLink();
            if ($htmlValues) {
                $variables = array_merge($variables, $this->getHtmlValues());
            } else {
                $variables = array_merge($variables, $this->getSimpleValues());
            }
            if ($htmlValues) {
                $this->cache[2] = $variables;
            } else {
                $this->cache[1] = $variables;
            }
        }
        if ($htmlValues) {
            return $this->cache[2];
        } else {
            return $this->cache[1];
        }
    }
}
