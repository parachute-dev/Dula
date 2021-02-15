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

namespace BlueFormBuilder\Core\Controller\Adminhtml\Form;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class ExportSubmissionCsv extends \BlueFormBuilder\Core\Controller\Adminhtml\Form
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'BlueFormBuilder::form';

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @param \Magento\Backend\App\Action\Context              $context     
     * @param \Magento\Framework\Stdlib\DateTime\DateTime      $dateTime    
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory 
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory
    ) {
        parent::__construct($context);
        $this->dateTime     = $dateTime;
        $this->_fileFactory = $fileFactory;
    }

    /**
     * Export products most viewed report to CSV format
     *
     * @return ResponseInterface
     */
    public function execute()
    {
        $form     = $this->_initForm();
        $date     = $this->dateTime->date('Y-m-d_H-i-s');
        $fileName = $form->getName() . '- Submissions - ' . $date . '.csv';
        $grid = $this->_view->getLayout()->createBlock(\BlueFormBuilder\Core\Block\Adminhtml\Form\SubmissionGrid::class);
        return $this->_fileFactory->create($fileName, $grid->getCsvFile(), DirectoryList::VAR_DIR);
    }
}
