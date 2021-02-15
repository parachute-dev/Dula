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
use Magento\Framework\Exception\LocalizedException;

class Upload extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;

    /**
     * @var \BlueFormBuilder\Core\Helper\Form
     */
    protected $formHelper;

    /**
     * @param \Magento\Framework\App\Action\Context           $context         
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     * @param \BlueFormBuilder\Core\Helper\Form               $formHelper      
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \BlueFormBuilder\Core\Helper\Form $formHelper
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
        $this->formHelper       = $formHelper;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        $result = [];
        try {
            $rand     = $this->getRequest()->getParam('rand');
            $recordId = $this->getRequest()->getParam('id');
            $elemId = str_replace(['bfb-control-', 'insert'], ['', ''], $this->getRequest()->getPostValue('id'));
            $formKey  = $this->getRequest()->getParam('key');

            $form = $this->formHelper->loadForm($formKey, 'bfb_form_key');

            $element = $form->getElement($elemId);

            if (!$element) throw new LocalizedException(__('This element no longer exists.'));

            if ($element->getElemId()) {
                $uploader = $this->_objectManager->create(
                    \Magento\MediaStorage\Model\File\Uploader::class,
                    ['fileId' => $recordId]
                );

                $allowedExttensions = str_replace(' ', '', $element->getConfig('allowed_exttensions'));
                if ($allowedExttensions) {
                    $allowedExttensions = explode(',', $allowedExttensions);
                    $uploader->setAllowedExtensions($allowedExttensions);
                }

                $imageAdapter = $this->_objectManager->get(\Magento\Framework\Image\AdapterFactory::class)->create();
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);

                $error = false;

                $maxFileSize = $element->getConfig('max_file_size');
                $minFileSize = $element->getConfig('min_file_size');

                if ($maxFileSize && $uploader->getFileSize() > $maxFileSize * 1024) {
                    $result = [
                        'error_message' => __('Files bigger than %1 KB not allowed', $maxFileSize)
                    ];
                    $error = true;
                }

                if ($minFileSize && $uploader->getFileSize() < $minFileSize * 1024) {
                    $result = [
                        'error_message' => __('Files smaller than %1 KB not allowed', $minFileSize)
                    ];
                    $error = true;
                }

                if (!$error) {
                    $mediaDirectory = $this->_objectManager->get(\Magento\Framework\Filesystem::class)->getDirectoryRead(DirectoryList::MEDIA);
                    $uploadDir = $mediaDirectory->getAbsolutePath(\BlueFormBuilder\Core\Model\File::UPLOAD_TMP) . '/' . $formKey;
                    $result    = $uploader->save($uploadDir);
                    
                    unset($result['tmp_name']);
                    unset($result['path']);

                    $result['file'] = $result['file'];
                    $result['rand'] = $rand;
                }
            }
        } catch (\LocalizedException $exception) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        } catch (\Exception $e) {
            $result = ['error' => __('Something went wrong while uploading the file.'), 'errorcode' => $e->getCode()];
        } 

        /** @var \Magento\Framework\Controller\Result\Raw $response */
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents(json_encode($result));
        return $response;
    }
}
