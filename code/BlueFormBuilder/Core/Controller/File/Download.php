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

namespace BlueFormBuilder\Core\Controller\File;

use Magento\Framework\App\ResponseInterface;

class Download extends \BlueFormBuilder\Core\Controller\Download
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
    }

    /**
     * Download file action
     *
     * @return void|ResponseInterface
     */
    public function execute()
    {
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $id      = $this->getRequest()->getParam('id', 0);
        $key     = $this->getRequest()->getParam('key', 0);
        $backend = $this->getRequest()->getParam('backend', 0);

        if (!$id || !$key) {
            $this->messageManager->addError(__('Something went wrong while getting the requested content.'));
            return $this->_redirect($baseUrl);
        }

        $collection = $this->_objectManager->create(
            \BlueFormBuilder\Core\Model\ResourceModel\File\Collection::class
        );
        
        $file = $collection->addFieldToFilter('element_id', $id)
        ->addFieldToFilter('file_hash', $key)
        ->getFirstItem();

        if (!$file->getId()) {
            $this->messageManager->addNotice(__("We can't find the file you requested."));
            return $this->_redirect($baseUrl);
        }

        $resource = $this->getFilePath(
            \BlueFormBuilder\Core\Model\File::UPLOAD_FOLDER,
            $file->getFile()
        );

        try {
            $this->_processDownload($resource, 'file');
            if (!$backend) {
                $numberOfDownloadsUsed = (int) $file->getNumberOfDownloads();
                $file->setData('number_of_downloads', $numberOfDownloadsUsed+1);
                $file->save();
            }
        } catch (\Exception $e) {
            $this->messageManager->addError(__('Something went wrong while getting the requested content.'));
        }
        return $this->_redirect($baseUrl);
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     *
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }
}
