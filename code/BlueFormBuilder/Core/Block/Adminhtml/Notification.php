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

namespace BlueFormBuilder\Core\Block\Adminhtml;

class Notification extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $_authorization;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $authSession;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory
     */
    protected $submissionCollectionFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context                                $context
     * @param \Magento\Backend\Model\Auth\Session                                    $authSession
     * @param \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory
     * @param array                                                                  $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \BlueFormBuilder\Core\Model\ResourceModel\Submission\CollectionFactory $submissionCollectionFactory,
        $data
    ) {
        parent::__construct($context, $data);
        $this->_authorization              = $context->getAuthorization();
        $this->authSession                 = $authSession;
        $this->submissionCollectionFactory = $submissionCollectionFactory;
    }

    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->addData([
            'cache_lifetime' => 86400,
            'cache_tags' => [\BlueFormBuilder\Core\Model\Submission::CACHE_TAG]
        ]);
    }

    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return [
            'BLUEFORMBUILDER_SUBMISSION_NOTIFICATION',
            $this->authSession->getUser()->getRole()->getId()
        ];
    }

    public function toHtml()
    {
        if (!$this->_authorization->isAllowed('BlueFormBuilder_Core::submission')) {
            return;
        }
        return parent::toHtml();
    }

    /**
     * @return int
     */
    public function getTotalUnread()
    {
        $collection = $this->submissionCollectionFactory->create();
        $collection->addFieldToFilter('read', \BlueFormBuilder\Core\Model\Submission::STATUS_UNREAD);
        return $collection->count();
    }
}
