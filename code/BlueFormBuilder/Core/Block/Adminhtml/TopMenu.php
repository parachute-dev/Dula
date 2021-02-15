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

namespace BlueFormBuilder\Core\Block\Adminhtml;

class TopMenu extends \Magento\Theme\Block\Html\Title
{
    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $_authorization;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array                                   $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        $data
    ) {
        parent::__construct($context, $data);
        $this->_authorization = $context->getAuthorization();
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        $links = $this->intLinks();
        if ($links) {
            foreach ($links as $z => &$_columnLinks) {
                if (!isset($_columnLinks['title'])) {
                    foreach ($_columnLinks as $k => $_link) {
                        if (isset($_link['resource']) && !$this->_isAllowedAction($_link['resource'])) {
                            unset($_columnLinks[$k]);
                        }
                    }
                } elseif (isset($_columnLinks['resource']) && !$this->_isAllowedAction($_columnLinks['resource'])) {
                    unset($links[$z]);
                }
            }
            return $links;
        }
        return false;
    }

    /**
     * Init menu items
     *
     * @return array
     */
    public function intLinks()
    {
        $links = [
            [
                [
                    'title'    => __('Add New Form'),
                    'link'     => $this->getUrl('blueformbuilder/form/new'),
                    'resource' => 'BlueFormBuilder_Core::form_save',
                    'class'    => 'bfb-add-new'
                ],
                [
                    'title'    => __('Manage Forms'),
                    'link'     => $this->getUrl('blueformbuilder/form/index'),
                    'resource' => 'BlueFormBuilder_Core::form'
                ],
                [
                    'title'    => __('Plugins'),
                    'link'     => $this->getUrl('blueformbuilder/form/new'),
                    'resource' => 'BlueFormBuilder_Core::form_save',
                    'class'    => 'bfb-plugins'
                ],
                [
                    'title'    => __('File Uploads'),
                    'link'     => $this->getUrl('blueformbuilder/files'),
                    'resource' => 'BlueFormBuilder_Core::form_save'
                ],
                [
                    'title'    => __('Settings'),
                    'link'     => $this->getUrl('adminhtml/system_config/edit/section/blueformbuilder'),
                    'resource' => 'BlueFormBuilder_Core::settings'
                ]
            ],
            [
                [
                    'title'    => __('Form Submissions'),
                    'link'     => $this->getUrl('blueformbuilder/submission/index'),
                    'resource' => 'BlueFormBuilder_Core::submission',
                    'class'    => 'bfb-submissions'
                ]
            ],
            [
                'class' => 'separator'
            ],
            [
                'title'  => __('User Guide'),
                'link'   => 'https://www.magezon.com/pub/media/productfile/blueformbuilder-v1.0.0-user_guides.pdf',
                'target' => '_blank'
            ],
            [
                'title'  => __('Change Log'),
                'link'   => 'https://www.magezon.com/blue-form-builder.html#release_notes',
                'target' => '_blank'
            ],
            [
                'title'  => __('Get Support'),
                'link'   => 'https://www.magezon.com/contact',
                'target' => '_blank'
            ]
        ];
        return $links;
    }
}
