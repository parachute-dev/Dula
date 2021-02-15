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

namespace BlueFormBuilder\Core\Block\Adminhtml\Submission\Edit\Tab;

class Advanced extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Prepare form
     *
     * @return $this
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareForm()
    {
        /* @var $model \BlueFormBuilder\Core\Model\Submission */
        $model = $this->_coreRegistry->registry('current_submission');
        
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('form_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => '',
                'class'  => 'fieldset-wide'
            ]
        );

        if ($model->getId()) {
            $fieldset->addField('submission_id', 'hidden', ['name' => 'submission_id']);
        }

        $fieldset->addField(
            'enable_trackback_page',
            'select',
            [
                'name'  => 'enable_trackback_page',
                'label' => __('Trackback Page'),
                'title' => __('Trackback Page'),
                'options' => [
                    0 => __('No'),
                    1 => __('Yes')
                ]
            ]
        );

        $fieldset->addField(
            'trackback_link',
            'note',
            [
                'name'  => 'trackback_link',
                'label' => __('Trackback Link'),
                'title' => __('Trackback Link'),
                'text'  => '<a href="' . $model->getTrackLink() . '" target="_blank">' . $model->getTrackLink() . '</a>'
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('More Informations');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('More Informations');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
