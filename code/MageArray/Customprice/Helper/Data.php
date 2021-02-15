<?php

namespace MageArray\Customprice\Helper;

/**
 * Class Data
 * @package MageArray\Customprice\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var
     */
    protected $_scopeConfig;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        $this->_scopeConfig = $context->getScopeConfig();
        parent::__construct($context);
    }

    /**
     * @param $storePath
     * @return mixed
     */
    public function getStoreConfig($storePath)
    {
        $storeConfig = $this->_scopeConfig
            ->getValue(
                $storePath,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
        return $storeConfig;
    }
}
