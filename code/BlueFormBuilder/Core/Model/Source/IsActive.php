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

namespace BlueFormBuilder\Core\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

class IsActive implements OptionSourceInterface
{
    /**
     * @var \BlueFormBuilder\Core\Model\Form
     */
    protected $form;

    /**
     * Constructor
     *
     * @param \BlueFormBuilder\Core\Model\Form $form
     */
    public function __construct(\BlueFormBuilder\Core\Model\Form $form)
    {
        $this->form = $form;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->form->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key
            ];
        }
        return $options;
    }
}
