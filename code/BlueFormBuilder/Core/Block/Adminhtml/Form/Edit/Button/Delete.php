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

namespace BlueFormBuilder\Core\Block\Adminhtml\Form\Edit\Button;

class Delete extends Generic
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getCurrentForm()->getId() && $this->_isAllowedAction('BlueFormBuilder_Core::form_delete')) {
            $data = [
                'label'    => __('Delete'),
                'class'    => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                    'Are you sure you want to do this?'
                ) . '\', \'' . $this->getUrl('*/*/delete', ['form_id' => $this->getCurrentForm()->getId()]) . '\')',
                'sort_order' => 30
            ];
        }
        return $data;
    }
}