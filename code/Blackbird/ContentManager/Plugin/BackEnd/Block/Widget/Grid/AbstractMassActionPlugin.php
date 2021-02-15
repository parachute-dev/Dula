<?php
/**
 * Blackbird ContentManager Module
 *
 * NOTICE OF LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@bird.eu so we can send you a copy immediately.
 *
 * @category        Blackbird
 * @package         Blackbird_ContentManager
 * @copyright       Copyright (c) 2019 Blackbird (https://black.bird.eu)
 * @author          Blackbird Team
 * @license         https://www.advancedcontentmanager.com/license/
 */
declare(strict_types=1);

namespace Blackbird\ContentManager\Plugin\BackEnd\Block\Widget\Grid;

use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Magento\Framework\Data\Collection\AbstractDb;

class AbstractMassActionPlugin
{
    /**
     * Fix the massaction Grid when not using UI Component
     *
     * @param \Magento\Backend\Block\Widget\Grid\Massaction\AbstractMassaction $subject
     * @param callable $proceed
     * @return string
     */
    public function aroundGetGridIdsJson(\Magento\Backend\Block\Widget\Grid\Massaction\AbstractMassaction $subject, callable $proceed): string
    {
        if (!$subject->getUseSelectAll()) {
            return '';
        }

        /** @var \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection $collection */
        $collection = clone $subject->getParentBlock()->getCollection();

        if ($collection instanceof AbstractDb) {
            $alias = $collection instanceof AbstractCollection ? 'e' : 'main_table';
            $idsSelect = clone $collection->getSelect();
            $idsSelect->reset(\Magento\Framework\DB\Select::ORDER);
            $idsSelect->reset(\Magento\Framework\DB\Select::LIMIT_COUNT);
            $idsSelect->reset(\Magento\Framework\DB\Select::LIMIT_OFFSET);
            $idsSelect->reset(\Magento\Framework\DB\Select::COLUMNS);
            $idsSelect->columns($subject->getMassactionIdField(), $alias);
            $idList = $collection->getConnection()->fetchCol($idsSelect);
        } else {
            $idList = $collection->setPageSize(0)->getColumnValues($subject->getMassactionIdField());
        }

        return implode(',', $idList);
    }
}