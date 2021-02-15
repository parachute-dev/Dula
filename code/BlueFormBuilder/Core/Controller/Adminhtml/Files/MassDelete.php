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

namespace BlueFormBuilder\Core\Controller\Adminhtml\Files;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory
     */
    protected $fileCollectionFactory;

    /**
     * @param \Magento\Backend\App\Action\Context                              $context
     * @param \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory
    ) {
        parent::__construct($context);
        $this->fileCollectionFactory = $fileCollectionFactory;
    }

    /**
     * Delete backups mass action
     *
     * @return \Magento\Backend\App\Action
     */
    public function execute()
    {
        $fileIds = $this->getRequest()->getParam('ids', []);

        if (!is_array($fileIds) || !count($fileIds)) {
            return $this->_redirect('*/files');
        }

        try {
            $collection = $this->fileCollectionFactory->create();
            $collection->addFieldToFilter('file_id', ['in' => $fileIds]);

            $fileDeleted = 0;
            foreach ($collection as $_file) {
                $_file->delete();
                $fileDeleted++;
            }

            $this->messageManager->addSuccess(
                __('A total of %1 file(s) have been deleted.', $fileDeleted)
            );
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $this->_redirect('*/files');
    }
}
