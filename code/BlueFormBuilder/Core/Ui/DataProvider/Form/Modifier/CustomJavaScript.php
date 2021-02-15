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

class CustomJavaScript extends AbstractModifier
{
    const GROUP_NAME               = 'custom_javascript';
    const GROUP_DEFAULT_SORT_ORDER = 700;

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        $this->prepareChildren();

        $this->createPanel();

        return $this->meta;
    }

	/**
     * Create Editor panel
     *
     * @return $this
     * @since 101.0.0
     */
    protected function createPanel()
    {
        $this->meta['settings']['children'] = array_replace_recursive(
            $this->meta['settings']['children'],
            [
                static::GROUP_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
								'label'                           => __('Custom Javascript'),
								'componentType'                   => Fieldset::NAME,
								'dataScope'                       => 'data',
								'collapsible'                     => true,
								'initializeFieldsetDataByDefault' => false,
								'sortOrder'                       => static::GROUP_DEFAULT_SORT_ORDER
                            ]
                        ]
                    ],
                    'children' => $this->getChildren()
                ]
            ]
        );
        return $this;
    }

    /**
     * @return \Magezon\UiBuilder\Data\Form\Element\Fieldset
     */
    public function prepareChildren()
    {
    	$this->addChildren(
            'js_on_pageload',
            'code',
            [
                'label'     => __('On Page Load'),
                'notice'    => __('Javascript to be executed on page load.'),
                'mode'      => 'javascript',
                'sortOrder' => 100
            ]
        );

        $this->addChildren(
            'js_before_submit',
            'code',
            [
                'label'     => __('Before Submit'),
                'notice'    => __('Javascript to be executed before form submission.'),
                'mode'      => 'javascript',
                'sortOrder' => 200
            ]
        );

        $this->addChildren(
            'js_after_submit',
            'code',
            [
                'label'     => __('After Submit'),
                'notice'    => __('Javascript to be executed after form submission, for example a Google Analytics tracking event.'),
                'mode'      => 'javascript',
                'sortOrder' => 300
            ]
        );
    }
}