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

use Magento\Framework\App\Filesystem\DirectoryList;
use BlueFormBuilder\Core\Model\File;

class DeleteFile extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @param \Magento\Framework\App\Action\Context               $context
     * @param \Magento\Framework\Controller\Result\RawFactory     $resultRawFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Framework\Filesystem                       $filesystem
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        parent::__construct($context);
        $this->resultRawFactory     = $resultRawFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->filesystem           = $filesystem;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $formKey = $this->getRequest()->getParam('key');
        $file    = $this->getRequest()->getParam('file');

        if (!$this->getRequest()->isAjax()) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }

        if (!$formKey || !$file) return;

        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $path           = $mediaDirectory->getAbsolutePath(File::UPLOAD_TMP . '/' . $formKey . $file);
        if (file_exists($path)) {
            $result['status'] = true;
            if (file_exists($path) && is_writable($path)) {
                unlink($path);
            }
        } else {
            $result['status'] = false;
        }
        
        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents(json_encode($result));
        return $response;
    }
}
