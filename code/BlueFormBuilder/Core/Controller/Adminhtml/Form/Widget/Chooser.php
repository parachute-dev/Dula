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

namespace BlueFormBuilder\Core\Controller\Adminhtml\Form\Widget;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\Controller\Result\RawFactory;

class Chooser extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'BlueFormBuilder_Core::form';

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var RawFactory
     */
    protected $resultRawFactory;

    /**
     * @param Context $context
     * @param LayoutFactory $layoutFactory
     * @param RawFactory $resultRawFactory
     */
    public function __construct(Context $context, LayoutFactory $layoutFactory, RawFactory $resultRawFactory)
    {
        $this->layoutFactory    = $layoutFactory;
        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }

    /**
     * Chooser Source action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Layout $layout */
        $layout = $this->layoutFactory->create();

        $uniqId = $this->getRequest()->getParam('uniq_id');
        $pagesGrid = $layout->createBlock(
            \BlueFormBuilder\Core\Block\Adminhtml\Form\Widget\Chooser::class,
            '',
            ['data' => ['id' => $uniqId]]
        );

        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultRawFactory->create();
        $resultRaw->setContents($pagesGrid->toHtml());
        return $resultRaw;
    }
}
