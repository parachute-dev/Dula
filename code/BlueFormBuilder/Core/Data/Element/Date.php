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

namespace BlueFormBuilder\Core\Data\Element;

class Date extends AbstractElement
{
    const FIELD_DAY_MONDAY    = "1";
    const FIELD_DAY_TUESDAY   = "2";
    const FIELD_DAY_WEDNESDAY = "3";
    const FIELD_DAY_THURDAY   = "4";
    const FIELD_DAY_FRIDAY    = "5";
    const FIELD_DAY_SATURDAY  = "6";
    const FIELD_DAY_SUNDAY    = "0";

    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
        parent::prepareForm();
        $this->prepareIconTab();
        $this->prepareValidationTab();
        $this->prepareAdvancedTab();
        return $this;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareValidationTab()
    {
        $validation = $this->addTab(
            'tab_validation',
            [
                'sortOrder'       => 40,
                'templateOptions' => [
                    'label' => __('Validation')
                ]
            ]
        );

            $container1 = $validation->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 10
                ]
            );

                $container1->addChildren(
                    'min_date',
                    'date',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'min_date',
                        'templateOptions' => [
                            'label' => __('Min Date')
                        ]
                    ]
                );

                $container1->addChildren(
                    'max_date',
                    'date',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'max_date',
                        'templateOptions' => [
                            'label' => __('Max Date')
                        ]
                    ]
                );

            $container2 = $validation->addContainerGroup(
                'container2',
                [
                    'sortOrder' => 20
                ]
            );

                $container2->addChildren(
                    'date_language',
                    'select',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'date_language',
                        'defaultValue'    => 'en',
                        'templateOptions' => [
                            'label'   => __('Language'),
                            'options' => $this->getDateLanguageOptions()
                        ]
                    ]
                );

                $container2->addChildren(
                    'date_format',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'date_format',
                        'defaultValue'    => 'dd/mm/yy',
                        'templateOptions' => [
                            'label'   => __('Format'),
                            'options' => $this->getDateFormatOptions()
                        ]
                    ]
                );

            $validation->addChildren(
                'days_allowed',
                'uiSelect',
                [
                    'sortOrder'       => 30,
                    'key'             => 'days_allowed',
                    'defaultValue'    => [
                        static::FIELD_DAY_MONDAY,
                        static::FIELD_DAY_TUESDAY,
                        static::FIELD_DAY_WEDNESDAY,
                        static::FIELD_DAY_THURDAY,
                        static::FIELD_DAY_FRIDAY,
                        static::FIELD_DAY_SATURDAY,
                        static::FIELD_DAY_SUNDAY
                    ],
                    'templateOptions' => [
                        'multiple' => true,
                        'label'    => __('Days Allowed'),
                        'options'  => $this->getDaysAllowedOptions()
                    ]
                ]
            );

            $validation->addChildren(
                'show_time',
                'toggle',
                [
                    'sortOrder'       => 40,
                    'key'             => 'show_time',
                    'templateOptions' => [
                        'label' => __('Show Time')
                    ]
                ]
            );

            $container4 = $validation->addContainerGroup(
                'container4',
                [
                    'sortOrder'      => 50,
                    'hideExpression' => '!model.show_time'
                ]
            );

                $container4->addChildren(
                    'show_hour',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'show_hour',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show Hour')
                        ]
                    ]
                );

                $container4->addChildren(
                    'min_hour',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'min_hour',
                        'defaultValue'    => 0,
                        'templateOptions' => [
                            'label'   => __('Min Hour'),
                            'options' => $this->getRange(0, 23, 1, true)
                        ]
                    ]
                );

                $container4->addChildren(
                    'max_hour',
                    'select',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'max_hour',
                        'defaultValue'    => 23,
                        'templateOptions' => [
                            'label'   => __('Max Hour'),
                            'options' => $this->getRange(0, 23, 1, true)
                        ]
                    ]
                );

                $container4->addChildren(
                    'hour_step',
                    'select',
                    [
                        'sortOrder'       => 40,
                        'key'             => 'hour_step',
                        'defaultValue'    => 1,
                        'templateOptions' => [
                            'label'   => __('Hour Step'),
                            'options' => $this->getRange(1, 24, 1, true)
                        ]
                    ]
                );

            $container5 = $validation->addContainerGroup(
                'container5',
                [
                    'sortOrder'      => 60,
                    'hideExpression' => '!model.show_time'
                ]
            );

                $container5->addChildren(
                    'show_minute',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'show_minute',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show Minute')
                        ]
                    ]
                );

                $container5->addChildren(
                    'min_minute',
                    'select',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'min_minute',
                        'defaultValue'    => 0,
                        'templateOptions' => [
                            'label'   => __('Min Minute'),
                            'options' => $this->getRange(0, 59, 1, true)
                        ]
                    ]
                );

                $container5->addChildren(
                    'max_minute',
                    'select',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'max_minute',
                        'defaultValue'    => 59,
                        'templateOptions' => [
                            'label'   => __('Max Minute'),
                            'options' => $this->getRange(0, 59, 1, true)
                        ]
                    ]
                );

                $container5->addChildren(
                    'minute_step',
                    'select',
                    [
                        'sortOrder'       => 40,
                        'key'             => 'minute_step',
                        'defaultValue'    => 10,
                        'templateOptions' => [
                            'label'   => __('Minute Step'),
                            'options' => $this->getRange(1, 60, 1, true)
                        ]
                    ]
                );


        return $validation;
    }

    /**
     * @return Magezon\Builder\Data\Form\Element\Fieldset
     */
    public function prepareAdvancedTab()
    {
        $advanced = $this->addTab(
            'tab_advanced',
            [
                'sortOrder'       => 50,
                'templateOptions' => [
                    'label' => __('Advanced')
                ]
            ]
        );

            $container1 = $advanced->addContainerGroup(
                'container1',
                [
                    'sortOrder' => 10
                ]
            );

                $container1->addChildren(
                    self::FIELD_REQUIRED,
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => self::FIELD_REQUIRED,
                        'templateOptions' => [
                            'label' => __('Required Field')
                        ]
                    ]
                );

                $container1->addChildren(
                    self::FIELD_AUTOTOFOCUS,
                    'toggle',
                    [
                        'sortOrder'       => 20,
                        'key'             => self::FIELD_AUTOTOFOCUS,
                        'templateOptions' => [
                            'label'        => __('Autofocus'),
                            'tooltipClass' => 'tooltip-bottom tooltip-bottom-right',
                            'tooltip'      => __('When present, it specifies that the element should automatically get focus when the page loads.')
                        ]
                    ]
                );

                $container1->addChildren(
                    'show_year',
                    'toggle',
                    [
                        'sortOrder'       => 30,
                        'key'             => 'show_year',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show Year in Select box')
                        ]
                    ]
                );

            $container2 = $advanced->addContainerGroup(
                'container2',
                [
                    'sortOrder' => 20
                ]
            );

                $container2->addChildren(
                    'show_month',
                    'toggle',
                    [
                        'sortOrder'       => 10,
                        'key'             => 'show_month',
                        'defaultValue'    => true,
                        'templateOptions' => [
                            'label' => __('Show Month in Select box')
                        ]
                    ]
                );

                $container2->addChildren(
                    'disable_past_dates',
                    'toggle',
                    [
                        'sortOrder'       => 20,
                        'key'             => 'disable_past_dates',
                        'templateOptions' => [
                            'label' => __('Disable Past Dates')
                        ]
                    ]
                );

                $container2->addChildren(
                    self::FIELD_READONLY,
                    'toggle',
                    [
                        'sortOrder'       => 30,
                        'key'             => self::FIELD_READONLY,
                        'templateOptions' => [
                            'label' => __('Read-Only Field')
                        ]
                    ]
                );

            $advanced->addChildren(
                self::FIELD_DEFAULT_VALUE,
                'date',
                [
                    'sortOrder'       => 30,
                    'key'             => self::FIELD_DEFAULT_VALUE,
                    'templateOptions' => [
                        'label' => __('Default Date'),
                        'note'  => __('Enter 0 to display current date time')
                    ]
                ]
            );

            $advanced->addChildren(
                'year_range',
                'text',
                [
                    'sortOrder'       => 40,
                    'key'             => 'year_range',
                    'templateOptions' => [
                        'label' => __('Year Range')
                    ],
                    'hideExpression' => '!model.show_year'
                ]
            );

        return $advanced;
    }

    /**
     * @return array
     */
    protected function getDateFormatOptions()
    {
        return [
            [
                'label' => 'M d, yy',
                'value' => 'M d, yy'
            ],
            [
                'label' => 'd M yy',
                'value' => 'd M yy'
            ],
            [
                'label' => 'yy-mm-dd',
                'value' => 'yy-mm-dd'
            ],
            [
                'label' => 'dd/mm/yy',
                'value' => 'dd/mm/yy'
            ],
            [
                'label' => 'dd.mm.yy',
                'value' => 'dd.mm.yy'
            ],
            [
                'label' => 'mm/dd/yy',
                'value' => 'mm/dd/yy'
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getDaysAllowedOptions()
    {
        return [
            [
                'label' => __('Monday'),
                'value' => static::FIELD_DAY_MONDAY
            ],
            [
                'label' => __('Tuesday'),
                'value' => static::FIELD_DAY_TUESDAY
            ],
            [
                'label' => __('Wednesday'),
                'value' => static::FIELD_DAY_WEDNESDAY
            ],
            [
                'label' => __('Thursday'),
                'value' => static::FIELD_DAY_THURDAY
            ],
            [
                'label' => __('Friday'),
                'value' => static::FIELD_DAY_FRIDAY
            ],
            [
                'label' => __('Saturday'),
                'value' => static::FIELD_DAY_SATURDAY
            ],
            [
                'label' => __('Sunday'),
                'value' => static::FIELD_DAY_SUNDAY
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getDateLanguageOptions()
    {
        return [
            [
                'label' => __('English'),
                'value' => 'en'
            ],
            [
                'label' => __('Afrikaans'),
                'value' => 'af'
            ],
            [
                'label' => __('Algerian Arabic'),
                'value' => 'ar-DZ'
            ],
            [
                'label' => __('Arabic'),
                'value' => 'ar'
            ],
            [
                'label' => __('Azerbaijani'),
                'value' => 'az'
            ],
            [
                'label' => __('Belarusian'),
                'value' => 'be'
            ],
            [
                'label' => __('Bulgarian'),
                'value' => 'bg'
            ],
            [
                'label' => __('Bosnian'),
                'value' => 'bs'
            ],
            [
                'label' => __('Inicialització'),
                'value' => 'ca'
            ],
            [
                'label' => __('Czech'),
                'value' => 'cs'
            ],
            [
                'label' => __('Welsh/UK'),
                'value' => 'cy-GB'
            ],
            [
                'label' => __('Danish'),
                'value' => 'da'
            ],
            [
                'label' => __('German'),
                'value' => 'de'
            ],
            [
                'label' => __('Greek'),
                'value' => 'el'
            ],
            [
                'label' => __('English/Australia'),
                'value' => 'el-AU'
            ],
            [
                'label' => __('English/UK'),
                'value' => 'en-GB'
            ],
            [
                'label' => __('English/New Zealand'),
                'value' => 'en-NZ'
            ],
            [
                'label' => __('Esperanto'),
                'value' => 'eo'
            ],
            [
                'label' => __('es'),
                'value' => 'es'
            ],
            [
                'label' => __('Estonian'),
                'value' => 'et'
            ],
            [
                'label' => __('Karrikas-ek'),
                'value' => 'eu'
            ],
            [
                'label' => __('Persian (Farsi)'),
                'value' => 'fa'
            ],
            [
                'label' => __('Finnish'),
                'value' => 'fi'
            ],
            [
                'label' => __('Faroese'),
                'value' => 'fo'
            ],
            [
                'label' => __('Canadian-French'),
                'value' => 'fr-CA'
            ],
            [
                'label' => __('Swiss-French'),
                'value' => 'fr-CH'
            ],
            [
                'label' => __('French'),
                'value' => 'fr'
            ],
            [
                'label' => __('Galician'),
                'value' => 'gl'
            ],
            [
                'label' => __('Hebrew'),
                'value' => 'he'
            ],
            [
                'label' => __('Hindi'),
                'value' => 'hi'
            ],
            [
                'label' => __('Croatian'),
                'value' => 'hr'
            ],
            [
                'label' => __('Hungarian'),
                'value' => 'hu'
            ],
            [
                'label' => __('Armenian'),
                'value' => 'hy'
            ],
            [
                'label' => __('Indonesian'),
                'value' => 'id'
            ],
            [
                'label' => __('Icelandic'),
                'value' => 'is'
            ],
            [
                'label' => __('Japanese'),
                'value' => 'ja'
            ],
            [
                'label' => __('Georgian'),
                'value' => 'ka'
            ],
            [
                'label' => __('Kazakh'),
                'value' => 'kk'
            ],
            [
                'label' => __('Khmer'),
                'value' => 'km'
            ],
            [
                'label' => __('Korean'),
                'value' => 'ko'
            ],
            [
                'label' => __('Kyrgyz'),
                'value' => 'ky'
            ],
            [
                'label' => __('Luxembourgish'),
                'value' => 'lb'
            ],
            [
                'label' => __('Lithuanian'),
                'value' => 'lt'
            ],
            [
                'label' => __('Latvian'),
                'value' => 'lv'
            ],
            [
                'label' => __('Macedonian'),
                'value' => 'mk'
            ],
            [
                'label' => __('Malayalam'),
                'value' => 'ml'
            ],
            [
                'label' => __('Malaysian'),
                'value' => 'ms'
            ],
            [
                'label' => __('Norwegian Bokmål'),
                'value' => 'nb'
            ],
            [
                'label' => __('Dutch (Belgium)'),
                'value' => 'nl-BE'
            ],
            [
                'label' => __('Dutch'),
                'value' => 'nl'
            ],
            [
                'label' => __('Norwegian Nynorsk'),
                'value' => 'nn'
            ],
            [
                'label' => __('Norwegian'),
                'value' => 'no'
            ],
            [
                'label' => __('Polish'),
                'value' => 'pl'
            ],
            [
                'label' => __('Brazilian'),
                'value' => 'pt-BR'
            ],
            [
                'label' => __('Portuguese'),
                'value' => 'pt'
            ],
            [
                'label' => __('Romansh'),
                'value' => 'rm'
            ],
            [
                'label' => __('Romanian'),
                'value' => 'ro'
            ],
            [
                'label' => __('Russian'),
                'value' => 'ru'
            ],
            [
                'label' => __('Slovak'),
                'value' => 'sk'
            ],
            [
                'label' => __('Slovenian'),
                'value' => 'sl'
            ],
            [
                'label' => __('Albanian'),
                'value' => 'sq'
            ],
            [
                'label' => __('Serbian'),
                'value' => 'sr'
            ],
            [
                'label' => __('Swedish'),
                'value' => 'sv'
            ],
            [
                'label' => __('Tamil'),
                'value' => 'ta'
            ],
            [
                'label' => __('Thai'),
                'value' => 'th'
            ],
            [
                'label' => __('Tajiki'),
                'value' => 'tj'
            ],
            [
                'label' => __('Turkish'),
                'value' => 'tr'
            ],
            [
                'label' => __('Ukrainian'),
                'value' => 'uk'
            ],
            [
                'label' => __('Vietnamese'),
                'value' => 'vi'
            ],
            [
                'label' => __('Chinese'),
                'value' => 'zh-CN'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
        return [
            'show_icon' => true,
            'icon'      => 'far mgz-fa-calendar'
        ];
    }
}