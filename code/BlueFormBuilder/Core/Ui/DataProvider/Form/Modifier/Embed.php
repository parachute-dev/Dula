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

namespace BlueFormBuilder\Core\Ui\DataProvider\Form\Modifier;

use Magento\Ui\Component\Form\Fieldset;
use Magezon\UiBuilder\Data\Form\Element\AbstractElement;
use Magezon\UiBuilder\Data\Form\Element\Factory;
use Magezon\UiBuilder\Data\Form\Element\CollectionFactory;

class Embed extends AbstractModifier
{
    const GROUP_EMBED_NAME               = 'embed';
    const GROUP_EMBED_DEFAULT_SORT_ORDER = 1100;

    /**
     * Get current form
     *
     * @return \BlueFormBuilder\Core\Model\Form
     * @throws NoSuchEntityException
     */
    public function getCurrentForm()
    {
        $form = $this->registry->registry('current_form');
        return $form;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        if (!$this->getCurrentForm()->getId()) {
            return $meta;
        }

        $this->meta = $meta;

        $this->prepareChildren();

        $this->createEmbedPanel();

        return $this->meta;
    }

    /**
     * @return \Magezon\UiBuilder\Data\Form\Element\Fieldset
     */
    public function prepareChildren()
    {
        $form   = $this->getCurrentForm();
        $formId = $form->getIdentifier();

        $this->addContainer(
            'shortcode',
            [
                'label'    => __('Short Code'),
                'template' =>'ui/form/components/complex',
                'content'  => '
	            <ul>
	                <li>
	                    <div class="bfb-embed-note">Insert the code below into WYSIWYG editor</div>
	                    <div class="bfb-embed-code"><textarea disabled>{{widget type="BlueFormBuilder\Core\Block\Widget\Form" code="' . $formId . '"}}</textarea></div>
	                </li>
	                <li>
	                    <div class="bfb-embed-note">Insert the code below into a template file</div>
	                    <div class="bfb-embed-code"><textarea disabled><?= $this->helper(\'BlueFormBuilder\Core\Helper\Data\')->renderForm("' . $formId . '") ?></textarea></div>
	                </li>
	                <li>
	                    <div class="bfb-embed-note">Insert the code below into a layout file</div>
	                    <div class="bfb-embed-code"><textarea rows="6" disabled>
<block class="BlueFormBuilder\Core\Block\Form" name="bfb-form">
        <arguments>
                <argument name="code" xsi:type="string">' . $formId . '</argument>
        </arguments>
</block>
</textarea></div>
	                </li>
	            </ul>'
            ]
        );
    }

    /**
     * Create Embed panel
     *
     * @return $this
     * @since 101.0.0
     */
    protected function createEmbedPanel()
    {
        $this->meta['settings']['children'] = array_replace_recursive(
            $this->meta['settings']['children'],
            [
                static::GROUP_EMBED_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label'         => __('Embed'),
                                'componentType' => Fieldset::NAME,
                                'dataScope'     => 'data',
                                'collapsible'   => true,
                                'sortOrder'     => static::GROUP_EMBED_DEFAULT_SORT_ORDER,
                                'additionalClasses' => [
                                    'bfb-embed' => true
                                ]
                            ]
                        ]
                    ],
                    'children' => $this->getChildren()
                ]
            ]
        );
        return $this;
    }
}
