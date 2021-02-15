<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageants\SimpleConfigurable\Model\Plugin;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Product extends \Magento\Catalog\Controller\Product\View
{
    /**
     * @var \Magento\Catalog\Helper\Product\View
     */
    protected $viewHelper;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Product view action
     *
     * @return \Magento\Framework\Controller\Result\Forward|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        

        // Get initial data from request
        $categoryId = (int) $this->getRequest()->getParam('category', false);
        $productId = (int) $this->getRequest()->getParam('id');
        $om = \Magento\Framework\App\ObjectManager::getInstance(); 
        $session = $om->get('Magento\Customer\Model\Session');
        $session->setCustomProductId($productId);
        
        if($this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface')->getValue(
            'SimpleConfigurable_config/SimpleConfigurable_settings/updatepageurl',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE) && $this->_objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface')->getValue(
            'SimpleConfigurable_config/SimpleConfigurable_settings/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            $parentIds = $this->_objectManager->get('Magento\ConfigurableProduct\Model\Product\Type\Configurable')
            ->getParentIdsByChild((int) $this->getRequest()->getParam('id'));

            $productId = array_shift($parentIds);

            if(!$productId){
                $productId = (int) $this->getRequest()->getParam('id');
            }
        }
                

        $specifyOptions = $this->getRequest()->getParam('options');

        if ($this->getRequest()->isPost() && $this->getRequest()->getParam(self::PARAM_NAME_URL_ENCODED)) {
            $product = $this->_initProduct();
            if (!$product) {
                return $this->noProductRedirect();
            }
            if ($specifyOptions) {
                $notice = $product->getTypeInstance()->getSpecifyOptionMessage();
                $this->messageManager->addNotice($notice);
            }
            if ($this->getRequest()->isAjax()) {
                $this->getResponse()->representJson(
                    $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode([
                        'backUrl' => $this->_redirect->getRedirectUrl()
                    ])
                );
                return;
            }
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setRefererOrBaseUrl();
            return $resultRedirect;
        }

        // Prepare helper and params
        $params = new \Magento\Framework\DataObject();
        $params->setCategoryId($categoryId);
        $params->setSpecifyOptions($specifyOptions);

        // Render page
        try {
            $page = $this->resultPageFactory->create();
            $this->viewHelper->prepareAndRender($page, $productId, $this, $params);
            return $page;
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return $this->noProductRedirect();
        } catch (\Exception $e) {
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $resultForward = $this->resultForwardFactory->create();
            $resultForward->forward('noroute');
            return $resultForward;
        }
    }
}
