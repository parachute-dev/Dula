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

namespace BlueFormBuilder\Core\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface SubmissionRepositoryInterface
{
    /**
     * Save submission.
     *
     * @param \BlueFormBuilder\Core\Api\Data\SubmissionInterface $submission
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\BlueFormBuilder\Core\Api\Data\SubmissionInterface $submission);

    /**
     * Retrieve submission.
     *
     * @param int $submissionId
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($submissionId);

    /**
     * Retrieve submissions matching the specified searchCriteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \BlueFormBuilder\Core\Api\Data\SubmissionSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete submission.
     *
     * @param \BlueFormBuilder\Core\Api\Data\SubmissionInterface $submission
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\BlueFormBuilder\Core\Api\Data\SubmissionInterface $submission);

    /**
     * Delete submission by ID.
     *
     * @param int $submissionId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($submissionId);
}
