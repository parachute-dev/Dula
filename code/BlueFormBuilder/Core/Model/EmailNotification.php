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

use \Magento\Framework\Exception\LocalizedException;
use Magento\Framework\DataObject;
use Magento\Customer\Helper\View as CustomerViewHelper;
use BlueFormBuilder\Core\Model\File as FileModel;
use Magento\Framework\App\Filesystem\DirectoryList;

class EmailNotification extends DataObject
{
    const TYPE_ADMIN    = 'admin';
    const TYPE_CUSTOMER = 'customer';

    /**
     * @var \BlueFormBuilder\Core\Model\Submission
     */
    protected $_submission;

    /**
     * @var array
     */
    protected $_templateVars;

    /**
     * @var array
     */
    protected $_submissionData = [];

    /**
     * @var Magento\Customer\Api\Data\CustomerInterface|null
     */
    protected $_customer;

    /**
     * @var \BlueFormBuilder\Core\Model\Form
     */
    protected $_form;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Email\Model\Template
     */
    protected $emailTemplate;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $_eventManager;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    protected $dataProcessor;

    /**
     * @var CustomerViewHelper
     */
    protected $customerViewHelper;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * @var \Magento\Customer\Model\CustomerRegistry
     */
    protected $customerRegistry;

    /**
     * @var \Magezon\Core\Helper\Data
     */
    protected $coreHelper;

    /**
     * @var \BlueFormBuilder\Core\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \BlueFormBuilder\Core\Mail\Template\TransportBuilderFactory
     */
    protected $transportBuilderFactory;

    /**
     * @param \Magento\Framework\Translate\Inline\StateInterface          $inlineTranslation       
     * @param \Magento\Framework\Message\ManagerInterface                 $messageManager          
     * @param \Magento\Email\Model\Template                               $emailTemplate           
     * @param \Magento\Framework\Event\ManagerInterface                   $eventManager            
     * @param \Magento\Customer\Api\CustomerRepositoryInterface           $customerRepository      
     * @param \Magento\Framework\App\ResourceConnection                   $resource                
     * @param \Magento\Framework\Reflection\DataObjectProcessor           $dataProcessor           
     * @param CustomerViewHelper                                          $customerViewHelper      
     * @param \Magento\Framework\Filesystem                               $filesystem              
     * @param \Magento\Customer\Model\CustomerRegistry                    $customerRegistry        
     * @param \Magezon\Core\Helper\Data                                   $coreHelper              
     * @param \BlueFormBuilder\Core\Helper\Data                           $dataHelper              
     * @param \BlueFormBuilder\Core\Mail\Template\TransportBuilderFactory $transportBuilderFactory 
     */
    public function __construct(
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Email\Model\Template $emailTemplate,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Reflection\DataObjectProcessor $dataProcessor,
        CustomerViewHelper $customerViewHelper,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Customer\Model\CustomerRegistry $customerRegistry,
        \Magezon\Core\Helper\Data $coreHelper,
        \BlueFormBuilder\Core\Mail\Template\TransportBuilderFactory $transportBuilderFactory
    ) {
        $this->inlineTranslation       = $inlineTranslation;
        $this->messageManager          = $messageManager;
        $this->emailTemplate           = $emailTemplate;
        $this->_eventManager           = $eventManager;
        $this->customerRepository      = $customerRepository;
        $this->_resource               = $resource;
        $this->dataProcessor           = $dataProcessor;
        $this->customerViewHelper      = $customerViewHelper;
        $this->mediaDirectory          = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->customerRegistry        = $customerRegistry;
        $this->coreHelper              = $coreHelper;
        $this->transportBuilderFactory = $transportBuilderFactory;
    }

    /**
     * @param \BlueFormBuilder\Core\Model\Submission $submission
     */
    public function setSubmission(\BlueFormBuilder\Core\Model\Submission $submission)
    {
        $this->_submission = $submission;
        $this->_form = $submission->getForm();
        $this->setVariables($submission->getVariables());
        return $this;
    }

    /**
     * @return \BlueFormBuilder\Core\Model\Submission $submission
     */
    public function getSubmission()
    {
        return $this->_submission;
    }

    /**
     * @return \BlueFormBuilder\Core\Model\Form
     */
    public function getForm()
    {
        return $this->_form;
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->_variables;
    }

    /**
     * @param array $variables
     */
    public function setVariables($variables)
    {
        $this->_variables = $variables;
        return $this;
    }

    public function sendEmail()
    {
        $form = $this->getForm();
        if ($form->getEnableCustomerNotification() && $form->getCustomerSenderEmail() && $form->getCustomerEmailBody()) {
            $this->sendCustomerNotification();
        }
        if ($form->getEnableNotification() && ($form->getRecipients() || $this->getAdminRecipientEmails()) && $form->getEmailBody()) {
            $this->sendAdminNotification();
        }
        $this->_submissionData['is_active'] = 1;
        $this->updateSubmission();
    }

    public function sendCustomerNotification()
    {
        $form    = $this->getForm();
        $subject = $this->getEmailSubject($form->getCustomerEmailSubject());
        $body    = $this->getEmailBody($form->getCustomerEmailBody());
        $emails  = $this->getCustomerRecipientEmails();
        if ($emails) {
            $attachments = $form->getCustomerAttachFiles() ? $this->getAttachments() : [];
            $this->send(
                static::TYPE_CUSTOMER,
                $form->getCustomerSenderName(),
                $form->getCustomerSenderEmail(),
                $emails,
                [],
                $form->getCustomerReplyTo(),
                $subject,
                $body,
                $attachments
            );
            $this->_submissionData['customer_send_count'] = $this->getSubmission()->getCustomerSendCount() + 1;
        }
        $this->_submissionData['customer_email_subject'] = $subject;
        $this->_submissionData['customer_email_body']    = $body;
        $this->_submissionData['customer_recipients']    = implode(', ', $emails);
    }

    private function getAdminRecipientEmails()
    {
        $form = $this->getForm();
        $recipients = explode(',', $form->getRecipients());
        if ($adminAdditionEmails = $this->getAdminAdditionEmails()) {
            $recipients = array_merge($recipients, $adminAdditionEmails);
        }
        $recipientEmails = [];
        foreach ($recipients as $_email) {
            $recipientEmails[] = trim($_email);
        }
        $conditionEmails = explode(',', $this->getSubmission()->getConditionEmails());
        if ($conditionEmails) {
            foreach ($conditionEmails as $_email) {
                $recipientEmails[] = trim($_email);
            }
        }
        return $this->prepareEmails($recipientEmails);
    }

    public function sendAdminNotification()
    {
        $form       = $this->getForm();
        $submission = $this->getSubmission();
        $recipientEmails     = $this->getAdminRecipientEmails();
        $recipientsBcc       = explode(',', $form->getRecipientsBcc());
        $recipientsBccEmails = [];
        foreach ($recipientsBcc as $_email) {
            $recipientsBccEmails[] = $_email;
        }
        $recipientsBccEmails = $this->prepareEmails($recipientsBccEmails);
        $subject             = $this->getEmailSubject($form->getEmailSubject());
        $body                = $this->getEmailBody($form->getEmailBody());
        if ($recipientEmails) {
            $attachments = $form->getAttachFiles() ? $this->getAttachments() : [];
            $this->send(
                static::TYPE_ADMIN,
                $submission->getSenderName(),
                $submission->getSenderEmail(),
                $recipientEmails,
                $recipientsBccEmails,
                $submission->getReplyTo(),
                $subject,
                $body,
                $attachments
            );
            $this->_submissionData['send_count'] = $this->getSubmission()->getSendCount() + 1;
        }
        $this->_submissionData['recipients']    = implode(',', $recipientEmails);
        $this->_submissionData['email_subject'] = $subject;
        $this->_submissionData['email_body']    = $body;
    }

    /**
     * Return file name form file path
     *
     * @param string $pathFile
     * @return string
     */
    public function getFileFromPathFile($pathFile)
    {
        return substr($pathFile, strrpos($pathFile, '/') + 1);
    }

    public function send($type, $senderName, $senderEmail, $recipientEmails, $recipientBccEmails, $replyTo, $subject, $body, $attachments = [])
    {
        if ($senderEmail) {
            $this->inlineTranslation->suspend();
            try {
                $submission = $this->getSubmission();
                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                $transport  = $this->transportBuilderFactory->create()
                ->setTemplateOptions([
                    'area'  => 'frontend',
                    'store' => $submission->getStoreId()
                ])
                ->setFrom([
                    'name'  => $senderName,
                    'email' => $senderEmail
                ])
                ->setTemplateVars($this->getTemplateVars())
                ->addTo($recipientEmails)
                ->addBcc($recipientBccEmails)
                ->setEmailSubject($subject)
                ->setEmailBody($body)
                ->setReplyTo($replyTo ? $replyTo : $senderEmail);
                if ($attachments) {
                    $_paths = [];
                    foreach ($attachments as $_attachment) {
                        if (!in_array($_attachment['path'], $_paths) && file_exists($_attachment['path'])) {
                            $fileName = $this->getFileFromPathFile($_attachment['file']);
                            $transport->addAttachment($fileName, file_get_contents($_attachment['path']), $_attachment['mine_type']);
                            $_paths[] = $_attachment['path'];
                        }
                    }
                }

                $this->_eventManager->dispatch(
                    'blueformbuilder_before_send_email_notification',
                    ['submission' => $submission, 'type' => $type, 'obj' => $this, 'transport' => $transport]
                );

                $transport = $transport->getTransport();
                $transport->sendMessage();
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(__('We can\'t send the email right now.'));
            }

            $this->inlineTranslation->resume();
        }
        return true;
    }

    /**
     * @return array
     */
    public function getCustomerRecipientEmails()
    {
        $variables = $this->getVariables();
        $elements  = $this->getForm()->getElements();
        $emails    = [];
        foreach ($elements as $element) {
            if ($element->getType() == 'bfb_email' && $element->getConfig('autoresponder') && isset($variables[$element->getConfig('elem_name')])) {
                $emails[] = $variables[$element->getConfig('elem_name')];
            }
        }
        return $this->prepareEmails($emails);
    }

    public function prepareEmails($emails)
    {
        $newEmails = [];
        foreach ($emails as $_email) {
            if (!$_email) continue;
            if ($email = $this->prepareEmail($_email)) {
                $newEmails[] = $email;
            }
        }
        return $newEmails;
    }

    public function prepareEmail($email)
    {
        $email = $this->processVariables(trim($email));
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $email;
        }
    }

    public function getEmailSubject($subject)
    {
        $templateVars = $this->getTemplateVars();
        $template = $this->emailTemplate;
        $template->setTemplateType('html');
        $templateSubject = $this->processVariables($subject);
        $template->setTemplateSubject($templateSubject);
        return $template->getProcessedTemplateSubject($templateVars);
    }

    public function getEmailBody($content)
    {
        $templateVars = $this->getTemplateVars();
        $template = $this->emailTemplate;
        $template->setTemplateType('html');
        $templateSubject = $this->processVariables($content);
        $template->setTemplateText($templateSubject);
        return $template->getProcessedTemplate($templateVars);
    }

    public function getTemplateVars()
    {
        if ($this->_templateVars==NULL) {
            $submission = $this->getSubmission(); 
            $vars['customer'] = $this->getCustomerData();
            $vars['store']    = $submission->getStore();
            $vars['form']     = $submission->getForm();
            $vars['product']  = $submission->getProduct();
            $this->_templateVars = $vars;
        }
        return $this->_templateVars;
    }

    /**
     * Retrieve customer model object
     *
     * @return CustomerData
     */
    public function getCustomerData()
    {
        if ($this->_customer === null) {
            $submission = $this->getSubmission();
            if ($submission->getCustomerId()) {
                try {
                    $customerData = $this->customerRepository->getById($submission->getCustomerId());
                    if ($customerData) {
                        $customer        = $this->getFullCustomerObject($customerData);
                        $this->_customer = $customer;
                    }
                } catch (\Exception $e) {

                }
            }
        }
        return $this->_customer;
    }

    /**
     * Create an object with data merged from Customer and CustomerSecure
     *
     * @param CustomerInterface $customer
     * @return \Magento\Customer\Model\Data\CustomerSecure
     */
    private function getFullCustomerObject($customer)
    {
        // No need to flatten the custom attributes or nested objects since the only usage is for email templates and
        // object passed for events
        $mergedCustomerData = $this->customerRegistry->retrieveSecureData($customer->getId());
        $customerData = $this->dataProcessor
            ->buildOutputDataArray($customer, \Magento\Customer\Api\Data\CustomerInterface::class);
        $mergedCustomerData->addData($customerData);
        $mergedCustomerData->setData('name', $this->customerViewHelper->getCustomerName($customer));
        return $mergedCustomerData;
    }

    protected function processVariables($content)
    {
        $variables = $this->getVariables();
        foreach ($variables as $name => $value) {
            $content = str_replace('[' . $name . ']', $value, $content);
        }
        return $this->coreHelper->filter($content);
    }

    public function updateSubmission()
    {
        $submission = $this->getSubmission();
        if ($this->_submissionData && $submission->getId()) {
            $connection = $this->_resource->getConnection();
            $table      = $this->_resource->getTableName('mgz_blueformbuilder_submission');
            $where      = ['submission_id = ?' => $submission->getId()];
            $connection->update($table, $this->_submissionData, $where);
        }
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        if (!$this->hasData('attachments')) {
            $directory = $this->mediaDirectory->getAbsolutePath(FileModel::UPLOAD_FOLDER);
            $attachments = [];
            $fileCollection = $this->getSubmission()->getFileCollection();
            foreach ($fileCollection as $file) {
                $data          = $file->getData();
                $data['path']  = $directory . $file->getFile();
                $attachments[] = $data;
            }
            $this->setAttachments($attachments);
        }
        return $this->getData('attachments');
    }
}