<?php

namespace MageArray\Customprice\Model\Attribute\Source;

/**
 * Class Globaloption
 * @package MageArray\Customprice\Model\Attribute\Source
 */
class Globaloption extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
            ['label' => 'Global', 'value' => '2'],
            ['label' => 'Yes', 'value' => '1'],
            ['label' => 'No', 'value' => '0']
        ];
        return $this->_options;
    }
}
