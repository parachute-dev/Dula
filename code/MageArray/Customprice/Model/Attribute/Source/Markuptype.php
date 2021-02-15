<?php

namespace MageArray\Customprice\Model\Attribute\Source;

/**
 * Class Markuptype
 * @package MageArray\Customprice\Model\Attribute\Source
 */
class Markuptype extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
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
            ['label' => 'Select Options', 'value' => ''],
            ['label' => 'Fixed', 'value' => 'fixed'],
            ['label' => 'Percent', 'value' => 'percent']
        ];
        return $this->_options;
    }
}
