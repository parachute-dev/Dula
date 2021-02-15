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
 * @copyright       Copyright (c) 2018 Blackbird (https://black.bird.eu)
 * @author          Blackbird Team
 * @license         https://www.advancedcontentmanager.com/license/
 */

namespace Blackbird\ContentManager\Block\Adminhtml\ContentType\Import;

use Magento\Backend\Block\Widget\Form\Generic;

/**
 * Class Form
 *
 * @package Blackbird\ContentManager\Block\Adminhtml\ContentType\Import
 */
class Form extends Generic
{
    /**
     * {@inheritdoc}
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create([
            'data' => [
                'id' => 'edit_form',
                'action' => $this->getUrl('*/*/postimport'),
                'method' => 'post',
                'enctype' => 'multipart/form-data',
            ],
        ]);
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
