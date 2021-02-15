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
use Magento\Ui\Component\Form;

class Builder extends AbstractModifier
{
    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

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
        $this->meta['builder'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label'         => __('Magezon Blue Form Builder'),
                        'collapsible'   => true,
                        'opened'        => false,
                        'componentType' => Form\Fieldset::NAME
                    ]
                ]
            ]
        ];
        return $this;
    }
}
