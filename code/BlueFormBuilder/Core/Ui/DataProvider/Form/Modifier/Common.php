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

class Common extends AbstractModifier
{
    const GROUP_NAME               = 'common';
    const GROUP_EMBED_DEFAULT_SORT_ORDER = 0;

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

        return $this->meta;
    }
}
