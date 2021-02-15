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

use BlueFormBuilder\Core\Api\Data\FormInterface;
use Magento\Framework\Model\AbstractModel;

class Form extends AbstractModel implements FormInterface
{
    const KEY_SUBMISSION_COUNT = 'submission_count';

    /**
     * BlueFormBuilder form cache tag
     */
    const CACHE_TAG = 'bfb_f';

    /**#@+
     * Form's statuses
     */
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    /**#@-*/

    /**#@-*/
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'blueformbuilder_form';

    /**
     * @var array
     */
    protected $_elements;

    /**
     * @var array
     */
    protected $_flatElements = [];

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\Collection
     */
    protected $submissionCollection;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magezon\Builder\Data\Elements
     */
    protected $builderElements;

    /**
     * @var \Magezon\Builder\Helper\Data
     */
    protected $builderHelper;

    /**
     * @var \BlueFormBuilder\Core\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @param \Magento\Framework\Model\Context                             $context            
     * @param \Magento\Framework\Registry                                  $registry           
     * @param \Magento\Framework\ObjectManagerInterface                    $objectManager      
     * @param \Magento\Store\Model\StoreManagerInterface                   $storeManager       
     * @param \Magezon\Builder\Data\Elements                               $builderElements    
     * @param \Magezon\Builder\Helper\Data                                 $builderHelper      
     * @param \BlueFormBuilder\Core\Helper\Data                            $dataHelper         
     * @param \Magezon\Core\Helper\Data                                    $coreHelper         
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource           
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection 
     * @param array                                                        $data               
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magezon\Builder\Data\Elements $builderElements,
        \Magezon\Builder\Helper\Data $builderHelper,
        \BlueFormBuilder\Core\Helper\Data $dataHelper,
        \Magezon\Core\Helper\Data $coreHelper,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection);
        $this->_objectManager  = $objectManager;
        $this->_storeManager   = $storeManager;
        $this->builderElements = $builderElements;
        $this->builderHelper   = $builderHelper;
        $this->dataHelper      = $dataHelper;
        $this->coreHelper      = $coreHelper;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\BlueFormBuilder\Core\Model\ResourceModel\Form::class);
    }

    /**
     * Prepare form's statuses.
     *
     * @return array
     */
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId()
    {
        return parent::getData(self::FIELD_FORM_ID);
    }

    /**
     * Set ID
     *
     * @param int $id
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setId($id)
    {
        return $this->setData(self::FIELD_FORM_ID, $id);
    }

    /**
     * Get form name
     *
     * @return string
     */
    public function getName()
    {
        return parent::getData(self::FIELD_NAME);
    }

    /**
     * Set form name
     *
     * @param string $name
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setName($name)
    {
        return $this->setData(self::FIELD_NAME, $name);
    }

    /**
     * Get form identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return parent::getData(self::FIELD_IDENTIFIER);
    }

    /**
     * Set form identifier
     *
     * @param string $identifier
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::FIELD_IDENTIFIER, $identifier);
    }

    /**
     * Get form redirect to
     *
     * @return string
     */
    public function getRedirectTo()
    {
        return parent::getData(self::FIELD_REDIRECT_TO);
    }

    /**
     * Set form redirect to
     *
     * @param string $redirectTo
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setRedirectTo($redirectTo)
    {
        return $this->setData(self::FIELD_REDIRECT_TO, $redirectTo);
    }

    /**
     * Show in toplink
     *
     * @return bool|null
     */
    public function getShowToplink()
    {
        return parent::getData(self::FIELD_SHOW_TOPLINK);
    }

    /**
     * Set show in toplinks
     *
     * @param int|bool $showToplink
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setShowToplink($showToplink)
    {
        return $this->setData(self::FIELD_SHOW_TOPLINK, $showToplink);
    }

    /**
     * Get success message
     *
     * @return string|null
     */
    public function getSuccessMessage()
    {
        return parent::getData(self::FIELD_SUCCESS_MESSAGE);
    }

    /**
     * Set success message
     *
     * @param string $successMessage
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setSuccessMessage($successMessage)
    {
        return $this->setData(self::FIELD_SUCCESS_MESSAGE, $successMessage);
    }

    /**
     * Get recipients
     *
     * @return string|null
     */
    public function getRecipients()
    {
        return parent::getData(self::FIELD_RECIPIENTS);
    }

    /**
     * Set recipients
     *
     * @param string $recipients
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setRecipients($recipients)
    {
        return $this->setData(self::FIELD_RECIPIENTS, $recipients);
    }

    /**
     * Get recipients bcss
     *
     * @return string|null
     */
    public function getRecipientsBcc()
    {
        return parent::getData(self::FIELD_RECIPIENTS_BBC);
    }

    /**
     * Set recipients bcc
     *
     * @param string $recipientsBcc
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setRecipientsBcc($recipientsBcc)
    {
        return $this->setData(self::FIELD_RECIPIENTS_BBC, $recipientsBcc);
    }

    /**
     * Get email subject
     *
     * @return string|null
     */
    public function getEmailSubject()
    {
        return parent::getData(self::FIELD_EMAIL_SUBJECT);
    }

    /**
     * Set email subject
     *
     * @param string $emailSubject
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setEmailSubject($emailSubject)
    {
        return $this->setData(self::FIELD_EMAIL_SUBJECT, $emailSubject);
    }

    /**
     * Get email body
     *
     * @return string|null
     */
    public function getEmailBody()
    {
        return parent::getData(self::FIELD_EMAIL_BODY);
    }

    /**
     * Set email body
     *
     * @param string $emailBody
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setEmailBody($emailBody)
    {
        return $this->setData(self::FIELD_EMAIL_BODY, $emailBody);
    }

    /**
     * Get customer sender name
     *
     * @return string|null
     */
    public function getCustomerSenderName()
    {
        return parent::getData(self::FIELD_CUSTOMER_SENDER_NAME);
    }

    /**
     * Set customer sender name
     *
     * @param string $customerSenderName
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerSenderName($customerSenderName)
    {
        return $this->setData(self::FIELD_CUSTOMER_SENDER_NAME, $customerSenderName);
    }

    /**
     * Get customer sender email
     *
     * @return string|null
     */
    public function getCustomerSenderEmail()
    {
        return parent::getData(self::FIELD_CUSTOMER_SENDER_EMAIL);
    }

    /**
     * Set customer sender email
     *
     * @param string $customerSenderEmail
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerSenderEmail($customerSenderEmail)
    {
        return $this->setData(self::FIELD_CUSTOMER_SENDER_EMAIL, $customerSenderEmail);
    }

    /**
     * Get customer email subject
     *
     * @return string|null
     */
    public function getCustomerEmailSubject()
    {
        return parent::getData(self::FIELD_CUSTOMER_EMAIL_SUBJECT);
    }

    /**
     * Set customer email subject
     *
     * @param string $customerEmailSubject
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerEmailSubject($customerEmailSubject)
    {
        return $this->setData(self::FIELD_CUSTOMER_EMAIL_SUBJECT, $customerEmailSubject);
    }

    /**
     * Get customer email body
     *
     * @return string|null
     */
    public function getCustomerEmailBody()
    {
        return parent::getData(self::FIELD_CUSTOMER_EMAIL_BODY);
    }

    /**
     * Set customer email body
     *
     * @param string $customerEmailSubject
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerEmailBody($customerEmailBody)
    {
        return $this->setData(self::FIELD_CUSTOMER_EMAIL_BODY, $customerEmailBody);
    }

    /**
     * Get meta title
     *
     * @return string|null
     */
    public function getMetaTitle()
    {
        return parent::getData(self::FIELD_META_TITLE);
    }

    /**
     * Set meta title
     *
     * @param string $metaTitle
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setMetaTitle($metaTitle)
    {
        return $this->setData(self::FIELD_META_TITLE, $metaTitle);
    }

    /**
     * Get meta description
     *
     * @return string|null
     */
    public function getMetaDescription()
    {
        return parent::getData(self::FIELD_META_DESCRIPTION);
    }

    /**
     * Set meta title
     *
     * @param string $metaDescription
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setMetaDescription($metaDescription)
    {
        return $this->setData(self::FIELD_META_DESCRIPTION, $metaDescription);
    }

    /**
     * Get meta keywords
     *
     * @return string|null
     */
    public function getMetaKeywords()
    {
        return parent::getData(self::FIELD_META_KEYWORDS);
    }

    /**
     * Set meta title
     *
     * @param string $metaKeywords
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setMetaKeywords($metaKeywords)
    {
        return $this->setData(self::FIELD_META_KEYWORDS, $metaKeywords);
    }

    /**
     * Is active
     *
     * @return bool|null
     */
    public function getIsActive()
    {
        return parent::getData(self::FIELD_IS_ACTIVE);
    }

    /**
     * Set is active
     *
     * @param int|bool $isActive
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::FIELD_IS_ACTIVE, $isActive);
    }

    /**
     * @return string|null
     */
    public function getCreationTime()
    {
        return parent::getData(self::FIELD_CREATION_TIME);
    }

    /**
     * @param string $creationTime
     * @return $this
     */
    public function setCreationTime($creationTime)
    {
        return $this->setData(self::FIELD_CREATION_TIME, $creationTime);
    }

    /**
     * @return string|null
     */
    public function getUpdateTime()
    {
        return parent::getData(self::FIELD_UPDATED_TIME);
    }

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdateTime($updateTime)
    {
        return $this->setData(self::FIELD_UPDATED_TIME, $updateTime);
    }

    /**
     * @return bool|null
     */
    public function getMultiplePage()
    {
        return parent::getData(self::FIELD_MULTIPLE_PAGE);
    }

    /**
     * @param int|bool $isMultiplePage
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setMultiplePage($isMultiplePage)
    {
        return $this->setData(self::FIELD_MULTIPLE_PAGE, $isMultiplePage);
    }

    /**
     * Get form redirect delay
     *
     * @return int
     */
    public function getRedirectDelay()
    {
        return parent::getData(self::FIELD_REDIRECT_DELAY);
    }

    /**
     * Set form redirect delay
     *
     * @param int $redirectDelay
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setRedirectDelay($redirectDelay)
    {
        return $this->setData(self::FIELD_REDIRECT_DELAY, $redirectDelay);
    }

    /**
     * Get submission prefix
     *
     * @return string
     */
    public function getSubmissionPrefix()
    {
        return parent::getData(self::FIELD_SUBMISSION_PREFIX);
    }

    /**
     * Set submission prefix
     *
     * @param string $submissionPrefix
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setSubmissionPrefix($submissionPrefix)
    {
        return $this->setData(self::FIELD_SUBMISSION_PREFIX, $submissionPrefix);
    }

    /**
     * Get attach files
     *
     * @return bool
     */
    public function getAttachFiles()
    {
        return parent::getData(self::FIELD_ATTACH_FILES);
    }

    /**
     * Set attach files
     *
     * @param bool $attachFiles
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setAttachFiles($attachFiles)
    {
        return $this->setData(self::FIELD_ATTACH_FILES, $attachFiles);
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
     * Set attach files
     *
     * @param string $customerReplyTo
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomerReplyTo($customerReplyTo)
    {
        return $this->setData(self::FIELD_CUSTOMER_REPLY_TO, $customerReplyTo);
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
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setReplyTo($replyTo)
    {
        return $this->setData(self::FIELD_REPLY_TO, $replyTo);
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
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setSenderEmail($senderEmail)
    {
        return $this->setData(self::FIELD_SENDER_EMAIL, $senderEmail);
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
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setSenderName($senderName)
    {
        return $this->setData(self::FIELD_SENDER_NAME, $senderName);
    }

    /**
     * Get page layout
     *
     * @return string
     */
    public function getPageLayout()
    {
        return parent::getData(self::FIELD_PAGE_LAYOUT);
    }

    /**
     * Set page layout
     *
     * @param string $pageLayout
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setPageLayout($pageLayout)
    {
        return $this->setData(self::FIELD_PAGE_LAYOUT, $pageLayout);
    }

    /**
     * Get custom class
     *
     * @return string
     */
    public function getCustomClasses()
    {
        return parent::getData(self::FIELD_CUSTOM_CLASSES);
    }

    /**
     * Set custom class
     *
     * @param string $customClasses
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomClasses($customClasses)
    {
        return $this->setData(self::FIELD_CUSTOM_CLASSES, $customClasses);
    }

    /**
     * Get custom css
     *
     * @return string
     */
    public function getCustomCss()
    {
        return parent::getData(self::FIELD_CUSTOM_CSS);
    }

    /**
     * Set custom css
     *
     * @param string $customCss
     * @return \BlueFormBuilder\Core\Api\Data\FormInterface
     */
    public function setCustomCss($customCss)
    {
        return $this->setData(self::FIELD_CUSTOM_CSS, $customCss);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \BlueFormBuilder\Core\Api\Data\FormExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return parent::getData(self::EXTENSION_ATTRIBUTES_KEY);
    }

    /**
     * Set an extension attributes object.
     *
     * @param \BlueFormBuilder\Core\Api\Data\FormExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\BlueFormBuilder\Core\Api\Data\FormExtensionInterface $extensionAttributes)
    {
        return $this->setData(self::EXTENSION_ATTRIBUTES_KEY, $extensionAttributes);
    }

    /**
     * Receive page store ids
     *
     * @return int[]
     */
    public function getStores()
    {
        return $this->hasData('stores') ? $this->getData('stores') : (array)$this->getData('store_id');
    }

    /**
     * Retrieve count submission of form
     *
     * @return int
     */
    public function getSubmissionCount()
    {
        if (!$this->hasData(self::KEY_SUBMISSION_COUNT)) {
            $count = $this->_getResource()->getSubmissionCount($this);
            $this->setData(self::KEY_SUBMISSION_COUNT, $count);
        }

        return $this->getData(self::KEY_SUBMISSION_COUNT);
    }

    /**
     * Get full form url
     *
     * @return string
     */
    public function getUrl()
    {
        $url   = $this->_storeManager->getStore()->getBaseUrl();
        $route = $this->dataHelper->getRoute();
        if ($route) {
            $route .= '/';
        }
        return $url . $route . $this->getIdentifier();
    }

    public function getRandomId()
    {
        return 'bfb' . $this->getId();
    }

    public function getContainerId()
    {
        return 'bfb-form-container' . $this->getRandomId();
    }

    public function getHtmlId()
    {
        return 'bfb-form-' . $this->getRandomId();
    }

    public function getProfile()
    {
        $profile = str_replace(',"enable_cache":true', '', $this->getData('profile'));
        return $this->builderHelper->prepareProfile($profile);
    }

    public function getElements($flat = true)
    {
        if ($this->_elements == null) {
            $elements = [];
            $profile = $this->getProfile();
            $this->flatElements($profile['elements']);
            foreach ($this->_flatElements as $_data) {
                if (!isset($_data['type'])) continue;
                $builderElement = $this->builderElements->getElement($_data['type']);
                if ($builderElement && $builderElement->getData('control')) {
                    $_model = '\BlueFormBuilder\Core\Model\Element';
                    if ($builderElement->getData('model')) {
                        $_model = $builderElement->getData('model');
                    }
                    $config = $_data;
                    unset($config['elements']);
                    $elemName = $_data['id'];
                    if (isset($_data['elem_name'])) {
                        $elemName = $_data['elem_name'];
                    }
                    $config['elem_name'] = $elemName;
                    $newElement = $this->_objectManager->create($_model, [
                        'data' => [
                            'elem_id'   => $config['id'],
                            'type'      => $config['type'],
                            'config'    => $config,
                            'elements'  => isset($_data['elements']) ? $_data['elements'] : [],
                            'elem_name' => $elemName
                        ]
                    ]);
                    if (!$newElement instanceof \BlueFormBuilder\Core\Model\ElementInterface) {
                        throw new \Magento\Framework\Exception\LocalizedException(__($_model . ' doessn\'n not implement \BlueFormBuilder\Core\Model\ElementInterface'));
                    }
                    $newElement->setBuilderElement($builderElement);
                    $elements[] = $newElement;
                }
            }
            $this->_elements = $elements;
        }
        return $this->_elements;
    }

    public function getElement($value, $field = 'id')
    {
        $elements = $this->getElements();
        foreach ($elements as $_element) {
            if ($_element->getConfig($field) == $value) {
                return $_element;
            }
        }
    }

    private function flatElements($_elements) {
        foreach ($_elements as $_data) {
            $this->_flatElements[] = $_data;
            if (isset($_data['elements'])) {
                $result = $this->flatElements($_data['elements']);
            }
        }
    }

    public function getAllElements()
    {
        return $this->_flatElements;
    }

    /**
     * Retrieve form submission collection
     *
     * @return \BlueFormBuilder\Core\Model\ResourceModel\Submission\Collection
     */
    public function getSubmissionCollection()
    {
        if ($this->submissionCollection === null) {
            $this->submissionCollection = $this->_getResource()->getSubmissionCollection($this->getId());
        }
        return $this->submissionCollection;
    }

    public function getConditional()
    {
        $conditional = $this->getData('conditional') ? $this->coreHelper->unserialize($this->getData('conditional')) : [];
        if (is_array($conditional)) {
            $result = [];
            foreach ($conditional as &$row) {
                if ($row) {
                    unset($row['option_id']);
                    unset($row['record_id']);
                    unset($row['initialize']);
                    if (isset($row['conditions']) && is_array($row['conditions'])) {
                        foreach ($row['conditions'] as &$row2) {
                            unset($row2['record_id']);
                            unset($row2['initialize']);
                            unset($row2['option_id']);
                        }
                    }
                    if (isset($row['actions']) && is_array($row['actions'])) {
                        foreach ($row['actions'] as &$row3) {
                            unset($row3['record_id']);
                            unset($row3['initialize']);
                            unset($row3['option_id']);
                        }
                    }
                    $result[] = $row;
                }
            }
        }
        return $conditional;
    }
}