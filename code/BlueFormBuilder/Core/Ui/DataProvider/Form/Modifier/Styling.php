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

class Styling extends AbstractModifier
{
    const GROUP_STYLING_NAME               = 'styling';
    const GROUP_STYLING_DEFAULT_SORT_ORDER = 300;
    const FIELD_SHADOW                     = 'shadow';
    const FIELD_CUSTOM_CLASS               = 'custom_class';
    const FIELD_CUSTOM_CSS                 = 'custom_css';

    const FORM_NAME                        = 'blueformbuilder_form_form';
    const TAB_APPEARANCE                   = 'tab_appearance';
    const TAB_DESIGN                       = 'tab_design';
    const TAB_COMMON                       = 'tab_common';
    const TAB_ADVANCED                     = 'tab_advanced';
    const TAB_ICON                         = 'tab_icon';
    const TAB_VALIDATION                   = 'tab_validation';
    const FIELD_RECORD_ID                  = 'record_id';
    const FIELD_ELEM_NAME                  = 'elem_name';
    const FIELD_LABEL                      = 'label';
    const FIELD_EXCLUDE_FROM_EMAIL         = 'exclude_from_email';
    const FIELD_EMAIL_LABEL                = 'email_label';
    const FIELD_LABEL_POSITION             = 'label_position';
    const FIELD_STATUS                     = 'status';
    const FIELD_TOOLTIP                    = 'tooltip';
    const FIELD_DESCRIPTION                = 'description';
    const FIELD_REQUIRED                   = 'required';
    const FIELD_VALIDATION                 = 'validation';
    const FIELD_ID_ATTRIBUTE               = 'elem_id';
    const FIELD_CSS_CLASSES                = 'css_classes';
    const FIELD_CONTAINER_CSS_CLASSES      = 'container_css_classes';
    const FIELD_PLACEHOLDER                = 'placeholder';
    const FIELD_DEFAULT_VALUE              = 'default_value';
    const FIELD_SIMPLY                     = 'simply';
    const FIELD_MARGIN_TOP                 = 'margin_top';
    const FIELD_MARGIN_RIGHT               = 'margin_right';
    const FIELD_MARGIN_BOTTOM              = 'margin_bottom';
    const FIELD_MARGIN_LEFT                = 'margin_left';
    const FIELD_MARGIN_UNIT                = 'margin_unit';
    const FIELD_BORDER_TOP                 = 'border_top';
    const FIELD_BORDER_RIGHT               = 'border_right';
    const FIELD_BORDER_BOTTOM              = 'border_bottom';
    const FIELD_BORDER_LEFT                = 'border_left';
    const FIELD_BORDER_UNIT                = 'border_unit';
    const FIELD_PADDING_TOP                = 'padding_top';
    const FIELD_PADDING_RIGHT              = 'padding_right';
    const FIELD_PADDING_BOTTOM             = 'padding_bottom';
    const FIELD_PADDING_LEFT               = 'padding_left';
    const FIELD_PADDING_UNIT               = 'padding_unit';
    const FIELD_BORDER_COLOR               = 'border_color';
    const FIELD_HOVER_FONT_COLOR           = 'hover_font_color';
    const FIELD_BORDER_STYLE               = 'border_style';
    const FIELD_FONT_COLOR                 = 'font_color';
    const FIELD_FONT_SIZE                  = 'font_size';
    const FIELD_FONT_STYLE                 = 'font_style';
    const FIELD_FONT_WEIGHT                = 'font_weight';
    const FIELD_BACKGROUND_COLOR           = 'background_color';
    const FIELD_HOVER_BACKGROUND_COLOR     = 'hover_background_color';
    const FIELD_BACKGROUND_IMAGE           = 'background_image';
    const FIELD_BACKGROUND_POSITION        = 'background_position';
    const FIELD_BACKGROUND_STYLE           = 'background_style';
    const FIELD_BORDER_RADIUS_TOP_LEFT     = 'border_radius_top_left';
    const FIELD_BORDER_RADIUS_TOP_RIGHT    = 'border_radius_top_right';
    const FIELD_BORDER_RADIUS_BOTTOM_RIGHT = 'border_radius_bottom_right';
    const FIELD_BORDER_RADIUS_BOTTOM_LEFT  = 'border_radius_bottom_left';
    const FIELD_BORDER_RADIUS_UNIT         = 'border_radius_unit';
    const FIELD_HIDDEN                     = 'hidden';
    const FIELD_READONLY                   = 'readonly';
    const FIELD_WIDTH                      = 'width';
    const FIELD_SORT_ORDER                 = 'sort_order';
    const FIELD_PATH                       = 'path';
    const FIELD_LEVEL                      = 'level';
    const FIELD_ELEM_PARENT                = 'elem_parent';
    const FIELD_HEIGHT                     = 'height';
    const FIELD_ELEM_TYPE                  = 'elem_type';
    const FIELD_SHOW_ICON                  = 'show_icon';
    const FIELD_ICON_POSITION              = 'icon_position';
    const FIELD_ICON_COLOR                 = 'icon_color';
    const FIELD_ICON                       = 'icon';
    const FIELD_ALIGN                      = 'align';
    const FIELD_ELEMENT_WIDTH              = 'elem_width';
    const FIELD_HOVER_BORDER_COLOR         = 'hover_border_color';

    const FIELDSET_DATA_SCOPE = 'blueformbuilder';

    /**
     * @var Factory
     */
    protected $_factoryElement;

    /**
     * @var CollectionFactory
     */
    protected $_factoryCollection;

    /**
     * @var \Magezon\UiBuilder\Data\Form\Element\Text
     */
    protected $text;

    /**
     * @param Factory                                      $factoryElement    
     * @param CollectionFactory                            $factoryCollection 
     * @param \Magento\Framework\Registry                  $registry          
     * @param \Magezon\UiBuilder\Data\Form\Element\Text $text              
     */
    public function __construct(
        Factory $factoryElement,
        CollectionFactory $factoryCollection,
        \Magento\Framework\Registry $registry,
        \Magezon\UiBuilder\Data\Form\Element\Text $text
    ) {
        parent::__construct($factoryElement, $factoryCollection, $registry);
        $this->text = $text;
    }

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;

        // $this->prepareChildren();

        // $this->createStylingPanel();

        return $this->meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        if (isset($data['simply'])) {
            $data['simply'] = (int)$data['simply'];
        }
        return $data;
    }

    /**
     * Create Editor panel
     *
     * @return $this
     * @since 101.0.0
     */
    protected function createStylingPanel()
    {
        $this->meta = array_replace_recursive(
            $this->meta,
            [
                static::GROUP_STYLING_NAME => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label'                           => __('Styling'),
                                'componentType'                   => Fieldset::NAME,
                                'collapsible'                     => true,
                                'initializeFieldsetDataByDefault' => false,
                                'sortOrder'                       => static::GROUP_STYLING_DEFAULT_SORT_ORDER,
                                'additionalClasses'               => 'bfb-styling',
                                'template'                        => 'BlueFormBuilder_Core/form/edit/styling',
                                'dataScope'                       => 'data'
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
            self::FIELD_SIMPLY,
            'checkbox',
            [
                'sortOrder'   => 0,
                'displayArea' => 'simply',
                'component'   => 'BlueFormBuilder_Core/js/modal/element/design-simply',
                'links'       => [
                    self::FIELD_MARGIN_LEFT  => '${ $.provider }:${ $.parentScope }.' . self::FIELD_MARGIN_LEFT . ':changed',
                    self::FIELD_BORDER_LEFT  => '${ $.provider }:${ $.parentScope }.' . self::FIELD_BORDER_LEFT . ':changed',
                    self::FIELD_PADDING_LEFT => '${ $.provider }:${ $.parentScope }.' . self::FIELD_PADDING_LEFT . ':changed'
                ],
                'listens' => [
                    self::FIELD_MARGIN_LEFT  => 'onMarginChanged',
                    self::FIELD_BORDER_LEFT  => 'onBorderChanged',
                    self::FIELD_PADDING_LEFT => 'onPaddingChanged'
                ]
            ]
        );

        // MARGIN
        $this->addChildren(
            self::FIELD_MARGIN_TOP,
            'text',
            [
                'sortOrder'         => 100,
                'displayArea'       => 'margin',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-top',
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . self::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_MARGIN_RIGHT,
            'text',
            [
                'sortOrder'         => 200,
                'displayArea'       => 'margin',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-right',
                'value'             => 'auto',
                'imports'           => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . self::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_MARGIN_BOTTOM,
            'text',
            [
                'default'           => 15,
                'sortOrder'         => 300,
                'displayArea'       => 'margin',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-bottom',
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . self::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_MARGIN_LEFT,
            'text',
            [
                'sortOrder'         => 400,
                'displayArea'       => 'margin',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-left',
                'value'             => 'auto'
            ]
        );

        $this->addChildren(
            self::FIELD_MARGIN_UNIT,
            'select',
            [
                'sortOrder'            => 500,
                'displayArea'          => 'margin-unit',
                'additionalClasses'    => 'bfb-design-margin-unit',
                'options'              => $this->text->getUnit(),
                'selectedPlaceholders' => false,
                'value'              => 'px'
            ]
        );

        // BORDER
        $this->addChildren(
            self::FIELD_BORDER_TOP,
            'text',
            [
                'sortOrder'         => 100,
                'displayArea'       => 'border',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-top',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . self::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_RIGHT,
            'text',
            [
                'sortOrder'         => 200,
                'displayArea'       => 'border',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-right',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . self::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_BOTTOM,
            'text',
            [
                'sortOrder'         => 300,
                'displayArea'       => 'border',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-bottom',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . self::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_LEFT,
            'text',
            [
                'sortOrder'         => 400,
                'displayArea'       => 'border',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-left',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_UNIT,
            'select',
            [
                'sortOrder'            => 500,
                'displayArea'          => 'border-unit',
                'additionalClasses'    => 'bfb-design-border-unit',
                'options'              => $this->text->getBorderUnit(),
                'selectedPlaceholders' => false,
                'value'              => 'px'
            ]
        );

        // PADDING
        $this->addChildren(
            self::FIELD_PADDING_TOP,
            'text',
            [
                'sortOrder'         => 100,
                'displayArea'       => 'padding',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-top',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . self::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_PADDING_RIGHT,
            'text',
            [
                'sortOrder'         => 200,
                'displayArea'       => 'padding',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-right',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . self::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_PADDING_BOTTOM,
            'text',
            [
                'sortOrder'         => 300,
                'displayArea'       => 'padding',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-bottom',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ],
                'imports' => [
                    'visible' => '!${ $.provider }:${ $.parentScope }.' . self::FIELD_SIMPLY
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_PADDING_LEFT,
            'text',
            [
                'sortOrder'         => 400,
                'displayArea'       => 'padding',
                'placeholder'       => '-',
                'additionalClasses' => 'bfb-design-left',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_PADDING_UNIT,
            'select',
            [
                'sortOrder'            => 500,
                'displayArea'          => 'padding-unit',
                'additionalClasses'    => 'bfb-design-padding-unit',
                'options'              => $this->text->getUnit(),
                'selectedPlaceholders' => false,
                'value'                => 'px'
            ]
        );

        $this->addChildren(
            self::FIELD_SHADOW,
            'boolean',
            [
                'label'       => __('Enable Shadow'),
                'sortOrder'   => 50,
                'default'     => 1,
                'displayArea' => 'right'
            ]
        );

        $this->addChildren(
            self::FIELD_WIDTH,
            'text',
            [
                'label'          => __('Width'),
                'sortOrder'      => 100,
                'displayArea'    => 'right',
                'additionalInfo' => 'Ex: 500px, 80%,etc'
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_COLOR,
            'text',
            [
                'label'             => __('Border Color'),
                'sortOrder'         => 200,
                'displayArea'       => 'right',
                'additionalClasses' => 'minicolors'
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_STYLE,
            'select',
            [
                'label'                => __('Border Style'),
                'sortOrder'            => 300,
                'displayArea'          => 'right',
                'options'              => $this->text->getBorderStyle(),
                'selectedPlaceholders' => [
                    'defaultPlaceholder' => __('Theme Default')
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_BACKGROUND_COLOR,
            'text',
            [
                'label'             => __('Background Color'),
                'sortOrder'         => 400,
                'displayArea'       => 'right',
                'additionalClasses' => 'minicolors'
            ]
        );

        $this->addChildren(
            self::FIELD_BACKGROUND_IMAGE,
            'image',
            [
                'label'        => __('Background Image'),
                'sortOrder'    => 500,
                'displayArea'  => 'right',
                'labelVisible' => false
            ]
        );

        $this->addChildren(
            self::FIELD_BACKGROUND_POSITION,
            'text',
            [
                'label'       => __('Background Position'),
                'sortOrder'   => 600,
                'displayArea' => 'right',
                'value'       => 'center'
            ]
        );

        $this->addChildren(
            self::FIELD_BACKGROUND_STYLE,
            'select',
            [
                'label'                => __('Background Style'),
                'sortOrder'            => 700,
                'displayArea'          => 'right',
                'options'              => $this->text->getBackgroundStyle(),
                'value'                => 'no-repeat',
                'selectedPlaceholders' => [
                    'defaultPlaceholder' => __('Theme Default')
                ]
            ]
        );

        // BORDER RADIUS
        $this->addChildren(
            self::FIELD_BORDER_RADIUS_TOP_LEFT,
            'text',
            [
                'sortOrder'         => 100,
                'displayArea'       => 'borderRadius',
                'additionalClasses' => 'bfb-design-top bfb-design-border-radius-top',
                'placeholder'       => '-',
                'notice'            => __('Border Radius'),
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_RADIUS_TOP_RIGHT,
            'text',
            [
                'sortOrder'         => 200,
                'displayArea'       => 'borderRadius',
                'additionalClasses' => 'bfb-design-right',
                'placeholder'       => '-',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_RADIUS_BOTTOM_RIGHT,
            'text',
            [
                'sortOrder'         => 300,
                'displayArea'       => 'borderRadius',
                'additionalClasses' => 'bfb-design-bottom',
                'placeholder'       => '-',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_RADIUS_BOTTOM_LEFT,
            'text',
            [
                'sortOrder'         => 400,
                'displayArea'       => 'borderRadius',
                'additionalClasses' => 'bfb-design-left',
                'placeholder'       => '-',
                'validation'        => [
                    'validate-not-negative-number' => true,
                    'validate-number'              => true
                ]
            ]
        );

        $this->addChildren(
            self::FIELD_BORDER_RADIUS_UNIT,
            'select',
            [
                'sortOrder'            => 500,
                'displayArea'          => 'borderRadius',
                'additionalClasses'    => 'bfb-design-border-radius-unit',
                'options'              => $this->text->getUnit(),
                'selectedPlaceholders' => false,
                'value'                => 'px'
            ]
        );

        $this->addChildren(
            static::FIELD_CUSTOM_CLASS,
            'text',
            [
                'label'             => __('Custom Class'),
                'sortOrder'         => 100,
                'additionalClasses' => 'bfb-custom-class',
                'displayArea'       => 'footer'
            ]
        );

        $this->addChildren(
            static::FIELD_CUSTOM_CSS,
            'code',
            [
                'label'             => __('Custom CSS'),
                'sortOrder'         => 200,
                'rows'              => 12,
                'additionalClasses' => 'bfb-custom-css',
                'displayArea'       => 'footer'
            ]
        );

        return $this;
    }
}
