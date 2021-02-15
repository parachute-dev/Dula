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

namespace BlueFormBuilder\Core\Controller\Form;

use Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\App\ObjectManager;

class Post extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \BlueFormBuilder\Core\Model\Form
     */
    protected $form;

    /**
     * @var \BlueFormBuilder\Core\Model\Submission
     */
    protected $submission;

    /**
     * @var array
     */
    protected $values;

    /**
     * @var array
     */
    protected $_attachments = [];

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $remoteAddress;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Captcha\Helper\Data
     */
    protected $captchaHelper;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    protected $timezoneInterface;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $countryFactory;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $moduleManager;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @var \Magezon\Builder\Helper\Data
     */
    protected $builderHelper;

    /**
     * @var \BlueFormBuilder\Core\Model\SubmissionFactory
     */
    protected $submissionFactory;

    /**
     * @var \BlueFormBuilder\Core\Helper\Form
     */
    protected $formHelper;

    /**
     * @var \BlueFormBuilder\Core\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory
     */
    protected $submissionCollectionFactory;

    /**
     * @var \BlueFormBuilder\Core\Model\FormProcessor
     */
    protected $formProcessor;

    /**
     * @param \Magento\Framework\App\Action\Context                                  $context                     
     * @param \Magento\Store\Model\StoreManagerInterface                             $storeManager                
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress                   $remoteAddress               
     * @param \Magento\Customer\Model\Session                                        $customerSession             
     * @param \Magento\Framework\View\LayoutFactory                                  $layoutFactory               
     * @param \Magento\Framework\App\ResourceConnection                              $resource                    
     * @param \Psr\Log\LoggerInterface                                               $logger                      
     * @param \Magento\Captcha\Helper\Data                                           $captchaHelper               
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface                   $timezoneInterface           
     * @param \Magento\Directory\Model\CountryFactory                                $countryFactory              
     * @param \Magento\Framework\Module\Manager                                      $moduleManager               
     * @param \Magezon\Core\Helper\Data                                              $coreHelper                  
     * @param \Magezon\Builder\Helper\Data                                           $builderHelper               
     * @param \BlueFormBuilder\Core\Model\SubmissionFactory                          $submissionFactory           
     * @param \BlueFormBuilder\Core\Helper\Form                                      $formHelper                  
     * @param \BlueFormBuilder\Core\Helper\Data                                      $dataHelper                  
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory 
     * @param \BlueFormBuilder\Core\Model\FormProcessor                              $formProcessor               
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Captcha\Helper\Data $captchaHelper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezoneInterface,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magezon\Core\Helper\Data $coreHelper,
        \Magezon\Builder\Helper\Data $builderHelper,
        \BlueFormBuilder\Core\Model\SubmissionFactory $submissionFactory,
        \BlueFormBuilder\Core\Helper\Form $formHelper,
        \BlueFormBuilder\Core\Helper\Data $dataHelper,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory,
        \BlueFormBuilder\Core\Model\FormProcessor $formProcessor
    ) {
        $this->_storeManager               = $storeManager;
        $this->remoteAddress              = $remoteAddress;
        $this->customerSession             = $customerSession;
        $this->layoutFactory               = $layoutFactory;
        $this->_resource                   = $resource;
        $this->logger                      = $logger;
        $this->captchaHelper               = $captchaHelper;
        $this->timezoneInterface           = $timezoneInterface;
        $this->countryFactory              = $countryFactory;
        $this->moduleManager               = $moduleManager;
        $this->coreHelper                  = $coreHelper;
        $this->builderHelper               = $builderHelper;
        $this->submissionFactory           = $submissionFactory;
        $this->formHelper                  = $formHelper;
        $this->dataHelper                  = $dataHelper;
        $this->submissionCollectionFactory = $submissionCollectionFactory;
        $this->formProcessor               = $formProcessor;
        parent::__construct($context);
    }

    /**
     * @param \BlueFormBuilder\Core\Model\Form $form
     */
    public function setForm(\BlueFormBuilder\Core\Model\Form $form)
    {
        $this->form = $form;
        return $this;
    }

    /**
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @param \BlueFormBuilder\Core\Model\Submission $submission
     */
    public function setSubmission(\BlueFormBuilder\Core\Model\Submission $submission)
    {
        $this->submission = $submission;
        return $this;
    }

    /**
     * @return \BlueFormBuilder\Core\Model\Submission $submission
     */
    public function getSubmission()
    {
        return $this->submission;
    }

    /**
     * @param array $postValue
     */
    public function setValues($values)
    {
        $this->values = $values;
        return $this;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $post             = $this->getRequest()->getPostValue();
        $formKey          = $this->getRequest()->getParam('bfb_form_key', null);
        $result['status'] = false;
        $store            = $this->_storeManager->getStore();

        try {
            $form = $this->_initForm();

            $post = $this->getFormPost();

            if ($post && $formKey) {

                $this->_resource->getConnection()->beginTransaction();

                $this->_eventManager->dispatch(
                    'bfb_submission_post_save_before',
                    ['action' => $this]
                );

                $this->verifyMagento2Captcha();

                $this->verifyReCaptcha();

                if ($this->hasSubmitted()) {
                    $result['message'] = $form->getDisableMultipleMessage();
                    $result['type']    = 'alert';
                    if ($this->getRequest()->isAjax()) {
                        $this->getResponse()->representJson(
                            $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($result)
                        );
                        return;
                    } else {
                        $this->messageManager->addError($result['message']);
                        $redirectTo = $this->_redirect->getRefererUrl();
                        $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
                        $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                        return $resultRedirect;
                    }
                }

                // Before Save
                foreach ($this->getElements() as $element) {
                    $val = isset($post[$element->getElemName()]) ? $post[$element->getElemName()] : '';
                    $element->setForm($form);
                    $element->setOrigValue($val);
                    $element->setPost($this->getFormPost());
                    $element->prepareValue($val);
                    $element->beforeSave();
                }

                // Prepare Values
                $values    = [];
                foreach ($this->getElements() as $element) {
                    $values[$element->getElemName()] = [
                        'simple' => $element->getValue(),
                        'html'   => $element->getHtmlValue(),
                        'email'  => $element->getEmailHtmlValue()
                    ];
                    if ($attachments = $element->getAttachments()) {
                        foreach ($attachments as $_attachment) {
                            $this->_attachments[] = $_attachment;
                        }
                    }
                }
                $this->setValues($values);

                // Save
                $submission = $this->saveSubmission();

                // After Save
                foreach ($this->getElements() as $element) {
                    $element->setSubmission($submission);
                    $element->afterSave();
                }

                $actions = $this->getConditionAction();
                if ($actions['redirect_to']) $form->setRedirectTo($actions['redirect_to']);
                $redirectTo = $form->getRedirectTo();
                if (!$redirectTo) $redirectTo = $store->getBaseUrl();
                if ($redirectTo === '/') {
                    $redirectTo = null;
                } else {
                    $redirectTo = $this->dataHelper->filter($redirectTo);
                }
                if (!$form->getRedirectDelay() && $redirectTo) $result['redirect'] = $redirectTo;
                $form->setRedirectTo($redirectTo);

                $result['message'] = $this->getSuccessMessage($redirectTo);
                $result['status']  = true;
                $result['key']     = $submission->getSubmissionHash();

                $this->formProcessor->deleteFormProcess($form->getId());

                $this->_eventManager->dispatch(
                    'blueformbuilder_submission_post_complete',
                    ['submission' => $submission, 'form' => $form]
                );

                $this->_resource->getConnection()->commit();
            }
        }  catch (LocalizedException $e) {
            $this->_resource->getConnection()->rollBack();
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->_resource->getConnection()->rollBack();
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(
                __('An error occurred while processing your form. Please try again later.')
            );
        }

        if ($this->getRequest()->isAjax()) {
            $this->getResponse()->representJson(
                $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($result)
            );
            return;
        } else {
            $redirectTo = $this->_redirect->getRefererUrl();
            if (isset($form) && $form->getRedirectTo()) $redirectTo = $form->getRedirectTo();
            $resultRedirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($redirectTo);
            return $resultRedirect;
        }
    }

    /**
     * @return array
     */
    public function getFormPost()
    {
        $form      = $this->getForm();
        $post      = $this->getRequest()->getPostValue();
        $postValue = [];
        foreach ($post as $_name => $val) {
            if ($val === 'bfbdisabled') continue;
            $element = $form->getElement($_name, 'elem_name');
            if ($element) {

                if ($element->getType() == 'bfb_address') {
                    if (isset($val['country'])) {
                        $country = $this->countryFactory->create()->loadByCode($val['country']);
                        $val['country'] = $country->getName();
                        $regionCollection = $country->getRegionCollection();
                        if (isset($val['state_id'])) {
                            if ($regionCollection->count()) {
                                $region = $regionCollection->getItemById($val['state_id']);
                                if ($region) {
                                    unset($val['state_id']);
                                    $val['state'] = $region->getName();
                                }
                            } else {
                                unset($val['state_id']);
                            }
                        }
                    }
                }

                if (is_array($val)) {
                    foreach ($val as &$_val) {
                        if (is_array($_val)) {
                            foreach ($_val as &$_val2) {
                                if (is_string($_val2)) {
                                    $_val2 = $this->dataHelper->removeScript(trim($_val2));
                                }
                            }
                        } else {
                            $_val = $this->dataHelper->removeScript(trim($_val));    
                        }
                    }
                } else {
                    $val = $this->dataHelper->removeScript(trim($val));
                }

                $postValue[$_name] = $val;
                if (isset($post[$_name . '_others'])) {
                    $postValue[$_name . '_others'] = $this->dataHelper->removeScript(trim($post[$_name . '_others']));
                }
            }
        }
        return $postValue;
    }

    /**
     * Initialize form instance from request data
     *
     * @return \BlueFormBuilder\Core\Model\Form|false
     */
    protected function _initForm()
    {
        $formKey = $this->getRequest()->getParam('bfb_form_key');
        $form    = $this->formHelper->loadForm($formKey, 'bfb_form_key');

        if (!$form->getId()) throw new LocalizedException(__('This form no longer exists.'));

        $this->setForm($form);

        return $form;
    }

    /**
     * @return array
     */
    protected function getElements()
    {
        $result   = [];
        $formPost = $this->getFormPost();
        $elements = $this->getForm()->getElements();
        foreach ($elements as $element) {
            if (isset($formPost[$element->getElemName()])) {
                $result[] = $element;
            }
        }
        return $result;
    }

    protected function verifyMagento2Captcha()
    {
        $post = $this->getRequest()->getPostValue();
        if (isset($post['captcha']) && is_array($post['captcha'])) {
            foreach ($post['captcha'] as $formId => $captchaString) {
                $captchaModel = $this->captchaHelper->getCaptcha($formId);
                if (!$captchaModel->isCorrect($captchaString)) {
                    throw new LocalizedException(__('Incorrect CAPTCHA.'));
                }
            }
        }
    }

    /**
     * @param  array $post
     * @return boolean
     */
    protected function verifyReCaptcha()
    {
        if ($this->moduleManager->isEnabled('Mageplaza_GoogleRecaptcha')) {
            $googleRecaptchaHelper = ObjectManager::getInstance()->get(\Mageplaza\GoogleRecaptcha\Helper\Data::class);
            if ($googleRecaptchaHelper->isEnabled() && $googleRecaptchaHelper->isCaptchaFrontend()) {
                return true;
            }
        }

        $form = $this->getForm();

        if ($form->getEnableRecaptcha()) {
            $this->verifyReCaptcha3();
        } else {
            $post      = $this->getRequest()->getPostValue();
            $valid     = true;
            $secretKey = $this->dataHelper->getConfig('recaptcha/secret_key');
            $publicKey = $this->dataHelper->getConfig('recaptcha/secret_key');
            $elements  = $form->getAllElements();
            $remoteIp  = $this->remoteAddress->getRemoteAddress();

            if (!$secretKey || !$publicKey) return true;
            foreach ($elements as $_element) {
                if ($_element['type'] === 'bfb_recaptcha') {
                    $valid = false;
                    if (isset($post['g-recaptcha-response'])) {
                        $postData = http_build_query([
                            'secret'   => $secretKey,
                            'response' => $post['g-recaptcha-response'],
                            'remoteip' => $remoteIp
                        ]);
                        $opts = [
                            'http' => [
                                'method'  => 'POST',
                                'header'  => 'Content-type: application/x-www-form-urlencoded',
                                'content' => $postData
                            ]
                        ];
                        $context  = stream_context_create($opts);
                        $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
                        $result   = json_decode($response);
                        $valid    = $result->success;
                    }
                    break;
                }
            }

            if (!$valid) throw new LocalizedException(__('Incorrect CAPTCHA.'));
            return $valid;
        }
    }

    private function verifyReCaptcha3()
    {
        $post      = $this->getRequest()->getPostValue();
        $secretKey = $this->dataHelper->getConfig('recaptcha3/secret_key');
        $remoteIp  = $this->remoteAddress->getRemoteAddress();
        if (isset($post['g-recaptcha-response']) && $remoteIp!='127.0.0.1') {
            $postData = http_build_query([
                'secret'   => $secretKey,
                'response' => $post['g-recaptcha-response'],
                'remoteip' => $remoteIp
            ]);
            $opts = [
                'http' => [
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postData
                ]
            ];
            $context  = stream_context_create($opts);
            $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
            $result   = json_decode($response);
            if (!$result->success) throw new LocalizedException(__('Incorrect CAPTCHA.'));
        }
    }

    /**
     * @return boolean
     */
    public function hasSubmitted()
    {
        $form  = $this->getForm();
        $valid = false;
        if ($form->getDisableMultiple()) {
            switch ($form->getDisableMultipleCondition()) {
                case 'customer_id':
                        $customerId = $this->customerSession->getCustomerId();
                        if ($customerId) {
                            $collection = $this->submissionCollectionFactory->create();
                            $collection->addFieldToFilter('customer_id', $customerId);
                            $collection->addFieldToFilter('form_id', $form->getId());
                            if ($collection->getSize()) {
                                $valid = true;
                            }
                        }
                    break;
                
                case 'ip_address':
                        $remoteId = $this->remoteAddress->getRemoteAddress();
                        $collection = $this->submissionCollectionFactory->create();
                        $collection->addFieldToFilter('remote_ip', $remoteId);
                        $collection->addFieldToFilter('form_id', $form->getId());
                        if ($collection->getSize()) {
                            $valid = true;
                        }
                    break;

                case 'form_fields':
                    if ($fields = $form->getDisableMultipleFields()) {
                        $post       = $this->getFormPost();
                        $fields     = $this->coreHelper->unserialize($fields);
                        $collection = $this->submissionCollectionFactory->create();
                        $collection->addFieldToFilter('form_id', $form->getId());
                        $cache = [];
                        foreach ($collection as $submission) {
                            $_valid = false;
                            $params = $this->coreHelper->unserialize($submission->getParams()); 
                            foreach ($fields as $k2 => $field) {
                                $element = $form->getElement($field);
                                if ($element) {
                                    $field = $element->getElemName();
                                    if (
                                        !isset($params[$field]) 
                                        || !isset($post[$field]) 
                                        || (isset($params[$field]) && isset($post[$field]) && (trim($params[$field]) != trim($post[$field])))
                                    ) {
                                        $_valid = true;
                                        break;
                                    }
                                }
                            }
                            if (!$_valid) {
                                $valid = true;
                                break;
                            }
                        }
                    }
                    break;
            }
        }
        return $valid;
    }

    /**
     * @param  array $post
     * @return \BlueFormBuilder\Core\Model\Submission
     */
    protected function saveSubmission()
    {
        $store     = $this->_storeManager->getStore();
        $post      = $this->getRequest()->getPostValue();
        $form      = $this->getForm();
        $profile   = $form->getProfile();
        $createdAt = $this->timezoneInterface->date()->format('Y-m-d H:i:s');
        $data      = [];

        $data['post']                     = $this->coreHelper->serialize($this->getFormPost());
        $data['values']                   = $this->coreHelper->serialize($this->getValues());
        $data['admin_submission_content'] = $this->getAdminSubmissionContent();
        $data['submission_content']       = $this->getSubmissionEmailContent();
        $actions = $this->getConditionAction();
        $data['condition_emails'] = implode(',', $actions['emails']);

        $data['form_params']            = $this->coreHelper->serialize($form->getData());
        $data['form_id']                = $form->getId();
        $data['elements']               = $this->coreHelper->serialize($profile['elements']);
        $data['customer_id']            = $this->customerSession->getId();
        $data['remote_ip']              = $this->remoteAddress->getRemoteAddress();
        $data['remote_ip_long']         = $this->remoteAddress->getRemoteAddress(true);
        $data['creation_time']          = $createdAt;
        $data['product_id']             = isset($post['product_id']) ? (int)$post['product_id'] : '';
        $data['store_id']               = $store->getId();
        $data['submitted_page']         = $this->_redirect->getRefererUrl();
        $data['brower']                 = $this->getRequest()->getServer('HTTP_USER_AGENT');
        $data['sender_name']            = $this->processVariables($form->getSenderName());
        $data['sender_email']           = $this->processVariables($form->getSenderEmail());
        $data['reply_to']               = $this->processVariables($form->getReplyTo());
        $data['recipients']             = $this->processVariables($form->getRecipients());
        $data['recipients_bcc']         = $this->processVariables($form->getRecipientsBcc());
        $data['email_subject']          = '';
        $data['email_body']             = '';
        $data['customer_sender_name']   = $this->processVariables($form->getCustomerSenderName());
        $data['customer_sender_email']  = $this->processVariables($form->getCustomerSenderEmail());
        $data['customer_reply_to']      = $this->processVariables($form->getCustomerReplyTo());
        $data['customer_email_subject'] = '';
        $data['customer_email_body']    = '';
        $data['read']                   = \BlueFormBuilder\Core\Model\Submission::STATUS_UNREAD;
        $data['admin_notification']     = $form->getEnableNotification();
        $data['customer_notification']  = $form->getEnableCustomerNotification();

        $submission = $this->submissionFactory->create();
        if (isset($post['submission_id'])) {
            $submission->load($post['submission_id']);
            if (!$submission->getId()) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('This submission no longer exists.')
                );
            }
        }

        $data['form'] = $form;
        $submission->addData($data);

        $this->_eventManager->dispatch(
            'blueformbuilder_submission_before_save',
            ['submission' => $submission, 'post' => $post, 'action' => $this]
        );

        $submission->save();

        $this->_eventManager->dispatch(
            'blueformbuilder_submission_after_save',
            ['submission' => $submission, 'post' => $post, 'action' => $this]
        );

        $this->setSubmission($submission);

        return $submission;
    }

    /**
     * @param  array $post
     * @return array
     */
    protected function getConditionAction()
    {
        $emails     = [];
        $redirectTo = '';
        $post       = $this->getFormPost();
        $form       = $this->getForm();
        if ($form->getConditional()) {
            $conditional = $this->coreHelper->unserialize($form->getConditional());
            foreach ($conditional as $_row) {
                if (isset($_row['conditions']) && $this->dataHelper->validateCondition($form, $_row['conditions'], $post)) {
                    if (isset($_row['actions'])) {
                        foreach ($_row['actions'] as $_row) {
                            if (isset($_row['action']) && ($_row['action'] == 'set' || $_row['action'] == 'rt')) {
                                $_row['value'] = trim($_row['value']);
                                if (($_row['action'] === 'set') && filter_var($_row['value'], FILTER_VALIDATE_EMAIL)) {
                                    $emails[] = $_row['value'];
                                }
                                if ($_row['action'] === 'rt' && $redirectTo == '') {
                                    $redirectTo = $this->coreHelper->filter($_row['value']);
                                }
                            }
                        }
                    }
                }
            }
        }
        return [
            'emails'      => $emails,
            'redirect_to' => $redirectTo
        ];
    }

    /**
     * @param  string $content
     * @return string         
     */
    public function processVariables($content)
    {
        $variables  = $this->getValues();
        $submission = $this->getSubmission();
        if ($submission) {
            $variables = array_merge($variables, $submission->getVariables());
            foreach ($variables as $name => $value) {
                $content = str_replace('[' . $name . ']', $value, $content);
                $content = str_replace('[<span>' . $name . '</span>]', $value, $content);
            }
        } else {
            foreach ($variables as $name => $element) {
                $content = str_replace('[' . $name . ']', $element['email'], $content);
                $content = str_replace('[<span>' . $name . '</span>]', $element['email'], $content);
            }
        }
        return $this->dataHelper->filter($content);
    }

    /**
     * @param  \BlueFormBuilder\Core\Model\Form $form
     * @return string
     */
    public function getSuccessMessage($redirectTo)
    {
        $layout = $this->layoutFactory->create();
        $block  = $layout->createBlock('\Magento\Framework\View\Element\Template')->setTemplate('BlueFormBuilder_Core::success.phtml');
        $block->setForm($this->getForm());
        $block->setRedirectTo($redirectTo);
        $html = $this->processVariables($block->toHtml());
        return $html;
    }

    /**
     * @return string
     */
    protected function getSubmissionEmailContent()
    {
        $form     = $this->getForm();
        $elements = [];
        foreach ($this->getElements() as $element) {
            $newElement = $element;
            if ($newElement->getConfig('exclude_from_email')) continue;
            if ($emailLabel = $newElement->getConfig('email_label')) {
                $config = $newElement->setConfig('label', $emailLabel);
            }
            $elements[] = $newElement;
        }

        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $this->layoutFactory->create();
        $block  = $layout->createBlock('BlueFormBuilder\Core\Block\Email\Message');
        $html   = $block->setTemplate('submission_content.phtml')
        ->setForm($form)
        ->setElements($elements)
        ->setPost($this->getFormPost())
        ->toHtml();
        return $html;
    }

    /**
     * @return string
     */
    protected function getAdminSubmissionContent()
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $this->layoutFactory->create();
        $form   = $this->getForm();
        $block  = $layout->createBlock('BlueFormBuilder\Core\Block\Email\Message');
        $html   = $block->setTemplate('submission_content.phtml')
        ->setForm($form)
        ->setElements($this->getElements())
        ->setPost($this->getFormPost())
        ->toHtml();
        return $html;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->_attachments;
    }
}
