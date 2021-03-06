<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Block\Adminhtml;

use Amasty\Base\Helper\Module;
use Amasty\Base\Model\ModuleListProcessor;
use Magento\Backend\Block\Template;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Extensions extends Field
{
    const SEO_PARAMS = '?utm_source=extension&utm_medium=backend&utm_campaign=ext_list';

    protected $_template = 'Amasty_Base::modules.phtml';

    /**
     * @var ModuleListProcessor
     */
    private $moduleListProcessor;
    /**
     * @var Module
     */
    private $moduleHelper;

    public function __construct(
        Template\Context $context,
        ModuleListProcessor $moduleListProcessor,
        Module $moduleHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->moduleListProcessor = $moduleListProcessor;
        $this->moduleHelper = $moduleHelper;
    }

    protected function _getElementHtml(AbstractElement $element)
    {
        return $this->toHtml();
    }

    /**
     * @return array
     */
    public function getModuleList()
    {
        return $this->moduleListProcessor->getModuleList();
    }

    /**
     * @return bool
     */
    public function isOriginMarketplace()
    {
        return $this->moduleHelper->isOriginMarketplace();
    }

    /**
     * return empty value where origin marketplace
     *
     * @return string
     */
    public function getSeoparams()
    {
        return !$this->isOriginMarketplace() ? self::SEO_PARAMS : '';
    }
}
