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

class LoadData extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\Config
     */
    protected $_catalogConfig;

    /**
     * @var \Magento\Catalog\Helper\Output
     */
    protected $outputHelper;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * @var \Magento\Customer\Helper\View
     */
    protected $customerViewHelper;

    /**
     * @var \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress
     */
    protected $_remoteAddress;

    /**
     * @var \Magento\Customer\Model\ResourceModel\Group\CollectionFactory
     */
    protected $customerGroupCollectionFactory;

    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $accountManagement;

    /**
     * @var \Magento\Customer\Helper\Address
     */
    protected $addressHelper;

    /**
     * @var \Magento\Customer\Model\Address\Mapper
     */
    protected $addressMapper;

	/**
	 * @var \Magezon\Core\Helper\Data
	 */
	protected $coreHelper;

	/**
	 * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory
	 */
	protected $submisionCollectionFactory;

    /**
     * @var \BlueFormBuilder\Core\Helper\Form
     */
    protected $formHelper;

    /**
     * @var \BlueFormBuilder\Core\Model\FormProcessor
     */
    protected $formProcessor;

    /**
     * @param \Magento\Framework\App\Action\Context                                  $context                        
     * @param \Magento\Store\Model\StoreManagerInterface                             $storeManager                   
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory         $productCollectionFactory       
     * @param \Magento\Catalog\Model\Config                                          $catalogConfig                  
     * @param \Magento\Catalog\Helper\Output                                         $outputHelper                   
     * @param \Magento\Customer\Model\Session                                        $customerSession                
     * @param \Magento\Customer\Helper\View                                          $customerViewHelper             
     * @param \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress                   $remoteAddress                  
     * @param \Magento\Customer\Model\ResourceModel\Group\CollectionFactory          $customerGroupCollectionFactory 
     * @param \Magento\Customer\Api\AccountManagementInterface                       $accountManagement              
     * @param \Magento\Customer\Helper\Address                                       $addressHelper                  
     * @param \Magento\Customer\Model\Address\Mapper                                 $addressMapper                  
     * @param \Magezon\Core\Helper\Data                                              $coreHelper                     
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $collectionFactory              
     * @param \BlueFormBuilder\Core\Helper\Form                                      $formHelper                     
     * @param \BlueFormBuilder\Core\Model\FormProcessor                              $formProcessor                  
     */
	public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Config $catalogConfig,
        \Magento\Catalog\Helper\Output $outputHelper,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Helper\View $customerViewHelper,
        \Magento\Framework\HTTP\PhpEnvironment\RemoteAddress $remoteAddress,
        \Magento\Customer\Model\ResourceModel\Group\CollectionFactory $customerGroupCollectionFactory,
        \Magento\Customer\Api\AccountManagementInterface $accountManagement,
        \Magento\Customer\Helper\Address $addressHelper,
        \Magento\Customer\Model\Address\Mapper $addressMapper,
        \Magezon\Core\Helper\Data $coreHelper,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $collectionFactory,
        \BlueFormBuilder\Core\Helper\Form $formHelper,
        \BlueFormBuilder\Core\Model\FormProcessor $formProcessor
    ) {
        parent::__construct($context);
        $this->_storeManager                  = $storeManager;
        $this->productCollectionFactory       = $productCollectionFactory;
        $this->_catalogConfig                 = $catalogConfig;
        $this->outputHelper                   = $outputHelper;
        $this->customerSession                = $customerSession;
        $this->customerViewHelper             = $customerViewHelper;
        $this->_remoteAddress                 = $remoteAddress;
        $this->customerGroupCollectionFactory = $customerGroupCollectionFactory;
        $this->accountManagement              = $accountManagement;
        $this->addressHelper                  = $addressHelper;
        $this->addressMapper                  = $addressMapper;
        $this->coreHelper                     = $coreHelper;
        $this->submisionCollectionFactory     = $collectionFactory;
        $this->formHelper                     = $formHelper;
        $this->formProcessor                  = $formProcessor;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
    	$params = $this->getRequest()->getParams();
    	$result['status'] = false;
    	try {
            $data = $this->getData();
            if (!empty($data)) {
                $result['data'] = $data;    
            } else {
                $result['sections'] = $this->getSections();    
            }
            $qs = str_replace('?', '', $this->getRequest()->getParam('qs'));
            parse_str($qs, $output);
            foreach ($output as &$_row) {
                if (strpos($_row, ',') !== FALSE) {
                    $_row = explode(',', $_row);
                }
            }
            if (isset($result['data']) && is_array($result['data'])) {
                $result['data'] = array_merge($result['data'], $output);
            } else {
                $result['data'] = $output;
            }
            $result['status'] = true;
		} catch (\Exception $e) {

		}
		$this->getResponse()->representJson(
            $this->_objectManager->get(\Magento\Framework\Json\Helper\Data::class)->jsonEncode($result)
        );
        return;
    }

    /**
     * @return array
     */
    public function getData()
    {
    	$data = [];
    	$params = $this->getRequest()->getParams();
    	if (isset($params['submission']) && $params['submission']) {
			$collection = $this->submisionCollectionFactory->create();
			$collection->addFieldToFilter('submission_hash', $params['submission']);
			$submission = $collection->getFirstItem();
            if ($submission->getId()) {
                $data = $this->coreHelper->unserialize($submission->getPost());
            }
    	} else {
            $formKey = $this->getRequest()->getParam('key');
            $form    = $this->formHelper->loadForm($formKey, 'bfb_form_key');
            $formId  = $form->getId();
            if ($formId && $form->getEnableAutosave()) {
                $progressData = $this->formProcessor->getFormProgress($formId);
                if ($progressData && isset($progressData['post']) && $progressData['post']) {
                    $data = $this->coreHelper->unserialize($progressData['post']);
                }
            }
        }
    	return $data;
    }

/**
     * Form view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function getSections()
    {
        $sections = $this->getRequest()->getParam('sections', null);
        $result   = [];

        if ($sections) {
            if (in_array('product', $sections)) {
                $result['product'] = $this->getProduct();
            }

            if (in_array('customer', $sections)) {
                $result['customer'] = $this->getCustomer();
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function getProduct()
    {
        $urlKey     = $this->getRequest()->getParam('urlkey');
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect($this->_catalogConfig->getProductAttributes());
        $collection->addAttributeToFilter('url_key', $urlKey);
        $product    = $collection->getFirstItem();
        $attributes = $this->_catalogConfig->getProductAttributes();
        $data       = [];
        if ($product->getId()) {
            foreach ($attributes as $attrCode) {
                $value           = $product->getData($attrCode);
                $html            = $this->outputHelper->productAttribute($product, $value, $attrCode);
                $data[$attrCode] = $html ? $html : '';
            }
        }
        $result = array_merge($product->getData(), $data);

        return $result;
    }

    /**
     * @return array
     */
    protected function getCustomer()
    {
        $result['group'] = __('Not Logged In');
        $result['ip']    = $this->_remoteAddress->getRemoteAddress();

        if (!$this->customerSession->getCustomerId()) {
            return $result;
        }
        $customer     = $this->customerSession->getCustomer();
        $customerData = $this->customerSession->getCustomerData();
        $groups       = $this->customerGroupCollectionFactory->create()->toOptionArray();
        foreach ($groups as $group) {
            if ($group['value'] === $customer->getGroupId()) {
                $result['group'] = $group['label'];
                break;
            }
        }

        $result['id']              = $customer->getId();
        $result['firstname']       = $customerData->getFirstname();
        $result['middlename']      = $customerData->getMiddlename();
        $result['lastname']        = $customerData->getLastname();
        $result['fullname']        = $this->customerViewHelper->getCustomerName($customerData);
        $result['dob']             = $customer->getDob() ? $customer->getDob() : '';
        $result['email']           = $customer->getEmail();
        $result['prefix']          = $customer->getPrefix() ? $customer->getPrefix() : '';
        $result['suffix']          = $customer->getSuffix() ? $customer->getSuffix() : '';
        $result['taxvat']          = $customer->getTaxvat() ? $customer->getTaxvat()  : '';
        $gender                    = $customer->getAttribute('gender')->getSource()->getOptionText($customer->getGender());
        $result['gender']          = $gender ? $gender  : '';
        $billingAddress            = $this->getBillingAddressHtml($customer);
        $result['billing_address'] = $billingAddress ? $billingAddress  : '';

        return $result;
    }

    /**
     * Retrieve billing address html
     *
     * @return \Magento\Framework\Phrase|string
     */
    public function getBillingAddressHtml($customer)
    {
        try {
            $address = $this->accountManagement->getDefaultBillingAddress($customer->getId());
        } catch (NoSuchEntityException $e) {
            return false;
        }

        if ($address === null) {
            return false;
        }

        return $this->addressHelper->getFormatTypeRenderer(
            'html'
        )->renderArray(
            $this->addressMapper->toFlatArray($address)
        );
    }
}