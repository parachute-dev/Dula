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

class Info extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
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
            'form_name',
            'note',
            [
                'name'  => 'form_name',
                'label' => __('Form'),
                'title' => __('Form'),
                'text'  =>  '<a href="' . $this->getUrl('blueformbuilder/form/edit', ['form_id' => $model->getFormId()]) . '" target="_blank">' . $model->getForm()->getName() . '</a>'
            ]
        );

        if (($customer = $model->getCustomer()) && $customer->getId()) {
            $fieldset->addField(
                'customer_name',
                'note',
                [
                    'name'  => 'customer_name',
                    'label' => __('Customer'),
                    'title' => __('Customer'),
                    'text'  =>  '<a href="' . $this->getUrl('customer/index/edit', ['id' => $customer->getId()]) . '" target="_blank">' . $customer->getName() . '</a>'
                ]
            );

            $fieldset->addField(
                'customer_email',
                'note',
                [
                    'name'  => 'customer_email',
                    'label' => __('Email'),
                    'title' => __('Email'),
                    'text'  =>  '<a href="mailto:' . $customer->getEmail() . '">' . $customer->getEmail() . '</a>'
                ]
            );
        }

        if ($product = $model->getProduct()) {
            if ($product->getId()) {
                $fieldset->addField(
                    'product_name',
                    'note',
                    [
                        'name'  => 'product_name',
                        'label' => __('Product'),
                        'title' => __('Product'),
                        'text'  =>  '<a href="' . $this->getUrl('catalog/product/edit', ['id' => $product->getId()]) . '" target="_blank">' . $product->getName() . '</a>'
                    ]
                );
            }
        }

        if ($model->getSubmittedPage()) {
            $fieldset->addField(
                'referer',
                'note',
                [
                    'name'  => 'referer',
                    'label' => __('Referer'),
                    'title' => __('Referer'),
                    'text'  => '<a href="' . $model->getSubmittedPage() . '" target="_blank">' . $model->getSubmittedPage() . '</a>'
                ]
            );
        }

        $fieldset->addField(
            'store_id',
            'note',
            [
                'name'  => 'store_id',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'text'  => $model->getStore()->getName()
            ]
        );

        $fieldset->addField(
            'submitted_at',
            'note',
            [
                'name'  => 'submitted_at',
                'label' => __('Created At'),
                'title' => __('Created At'),
                'text'  => $model->getCreatedAtFormatted()
            ]
        );

        $fieldset->addField(
            'remote_ip',
            'note',
            [
                'name'  => 'remote_ip',
                'label' => __('IP Address'),
                'title' => __('IP Address'),
                'text'  => $model->getRemoteIp()
            ]
        );

        $fieldset->addField(
            'brower',
            'note',
            [
                'name'  => 'brower',
                'label' => __('User Agent'),
                'title' => __('User Agent'),
                'text'  => $model->getBrower()
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
