<?php

namespace MageArray\Customprice\Model\Attribute\Source;

/**
 * Class ApplyCsvType
 * @package MageArray\Customprice\Model\Attribute\Source
 */
class ApplyCsvType extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var
     */
    protected $_options;

    /**
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = [
            ['label' => 'Disable', 'value' => '0'],
            ['label' => 'Simple CSV Pricing', 'value' => 'simple'],
            ['label' => 'Option Wise (Multiple) CSV Pricing', 'value' => 'multiple'],
            ['label' => 'One Dimensional CSV Pricing', 'value' => 'dimensional']
        ];
        return $this->_options;
    }
}
