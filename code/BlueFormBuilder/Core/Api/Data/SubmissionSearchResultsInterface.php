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

namespace BlueFormBuilder\Core\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface SubmissionSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get submissions list.
     *
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface[]
     */
    public function getItems();

    /**
     * Set submissions list.
     *
     * @param \BlueFormBuilder\Core\Api\Data\SubmissionInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
