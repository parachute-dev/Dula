<?php

namespace MageArray\Customprice\Controller\Adminhtml\Customcsv;

use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class Index
 * @package MageArray\Customprice\Controller\Adminhtml\Customcsv
 */
class Index extends \Magento\Backend\App\Action
{

    /**
     * @param Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        parent::__construct($context);
        $this->resultRawFactory = $resultRawFactory;
    }

    /**
     * Check the permission to run it
     *
     * @return bool
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        if ($data['option_id'] != "") {
            $csvfileid = 'csvfileupload' . $data['option_id'];
        } else {
            $csvfileid = 'csvfileupload';
        }

        try {
            $uploader = $this->_objectManager->create(
                \Magento\MediaStorage\Model\File\Uploader::class,
                ['fileId' => $csvfileid]
            );
            $uploader->setAllowedExtensions(['csv']);

            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);

            $mediaDirectory = $this->_objectManager
                ->get(\Magento\Framework\Filesystem::class)
                ->getDirectoryRead(DirectoryList::MEDIA);

            $result = $uploader
                ->save($mediaDirectory->getAbsolutePath('csvfiles/allfiles'));
        } catch (\Exception $e) {
            $result = [];
            $result['error'] = $e->getMessage();
            $result['errorcode'] = 0;
        }
        $response = $this->resultRawFactory->create();
        $response->setHeader('Content-type', 'text/plain');
        $response->setContents(json_encode($result));
        return $response;
    }
}
