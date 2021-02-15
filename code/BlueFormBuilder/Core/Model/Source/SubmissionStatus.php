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

class SubmissionStatus implements OptionSourceInterface
{
    /**
     * @var \BlueFormBuilder\Core\Model\Submission
     */
    protected $blueformbuilderSubmission;

    /**
     * Constructor
     *
     * @param \BlueFormBuilder\Core\Model\Submission $blueformbuilderSubmission
     */
    public function __construct(\BlueFormBuilder\Core\Model\Submission $blueformbuilderSubmission)
    {
        $this->blueformbuilderSubmission = $blueformbuilderSubmission;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->blueformbuilderSubmission->getAvailableStatuses();
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
