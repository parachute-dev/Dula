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

namespace BlueFormBuilder\Core\Controller\Adminhtml;

abstract class Form extends \Magento\Backend\App\Action
{
    /**
     * Initialize requested form and put it into registry.
     *
     * @return \BlueFormBuilder\Core\Model\Form|false
     */
    protected function _initForm()
    {
        $formId = $this->resolveFormId();
        $form   = $this->_objectManager->create(\BlueFormBuilder\Core\Model\Form::class);

        if ($formId) {
            $form->load($formId);
        }

        $this->_objectManager->get(\Magento\Framework\Registry::class)->register('form', $form);
        $this->_objectManager->get(\Magento\Framework\Registry::class)->register('current_form', $form);
        return $form;
    }

    /**
     * Resolve Form Id (from get or from post)
     *
     * @return int
     */
    public function resolveFormId()
    {
        $formId = (int) $this->getRequest()->getParam('id', false);

        return $formId ?: (int) $this->getRequest()->getParam('form_id', false);
    }
}
