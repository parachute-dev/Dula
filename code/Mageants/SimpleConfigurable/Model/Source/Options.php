<?php
/**
 * @category Mageants ConfigurablePreselect
 * @package Mageants_ConfigurablePreselect
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <info@mageants.com>
 */

namespace Mageants\SimpleConfigurable\Model\Source;

use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class Options implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Return array of customer groups
     *
     * @return array
     */
    public function toOptionArray()
    {
       
        $preselectOptions = [];
        $preselectOptions[0] = [
                'label' => "The First Options",
                'value' => 0,
            ];
        $preselectOptions[1] = [
                'label' => "Select from Product",
                'value' => 1,
            ];
        $preselectOptions[2] = [
                'label' => "Select Highest Price Product",
                'value' => 2,
            ];
        $preselectOptions[3] = [
                'label' => "Select Lowest Price Product",
                'value' => 3,
            ];
        
        return $preselectOptions;
    }
}
