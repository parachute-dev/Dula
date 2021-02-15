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

class Recaptcha extends AbstractElement
{
    /**
     * Prepare modal components
     */
    public function prepareForm()
    {
    	parent::prepareForm();
    	$this->prepareAdvancedTab();
    	return $this;
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
	                'recaptcha_type',
	                'select',
	                [
						'sortOrder'       => 10,
						'key'             => 'recaptcha_type',
						'defaultValue'    => 'image',
						'templateOptions' => [
							'label'   => __('reCaptcha Type'),
							'options' => $this->getTypeOptions()
	                    ]
	                ]
	            );

            	$container1->addChildren(
	                'recaptcha_language',
	                'select',
	                [
						'sortOrder'       => 20,
						'key'             => 'recaptcha_language',
						'defaultValue'    => 'en',
						'templateOptions' => [
							'label'   => __('reCaptcha Language'),
							'options' => $this->getLanguageOptions()
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
		            'recaptcha_theme',
		            'select',
		            [
						'key'             => 'recaptcha_theme',
						'sortOrder'       => 10,
						'defaultValue'    => 'light',
						'templateOptions' => [
							'label'   => __('reCaptcha Theme'),
							'options' => $this->getThemeOptions(),
		                ]
		            ]
		        );

            	$container2->addChildren(
		            'recaptcha_size',
		            'select',
		            [
		                'key'             => 'recaptcha_size',
		                'sortOrder'       => 20,
						'defaultValue'    => 'normal',
		                'templateOptions' => [
							'label' => __('reCaptcha Size'),
							'options' => $this->getSizeOptions()
		                ]
		            ]
		        );

        	$advanced->addChildren(
	            'recaptcha_hide_logged_in',
	            'toggle',
	            [
	                'key'             => 'recaptcha_hide_logged_in',
	                'sortOrder'       => 30,
	                'templateOptions' => [
						'label' => __('Hide if user is logged in')
	                ]
	            ]
	        );

        return $advanced;
    }

    /**
     * @return array
     */
    public function getTypeOptions()
    {
        return [
            [
                'label' => __('Image'),
                'value' => 'image'
            ],
            [
                'label' => __('Audio'),
                'value' => 'audio'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getLanguageOptions()
    {
        return [
            [
                'value' => 'ar',
                'label' => 'Arabic'
            ],
            [
                'value' => 'af',
                'label' => 'Afrikaans'
            ],
            [
                'value' => 'am',
                'label' => 'Amharic'
            ],
            [
                'value' => 'hy',
                'label' => 'Armenian'
            ],
            [
                'value' => 'az',
                'label' => 'Azerbaijani'
            ],
            [
                'value' => 'eu',
                'label' => 'Basque'
            ],
            [
                'value' => 'bn',
                'label' => 'Bengali'
            ],
            [
                'value' => 'bg',
                'label' => 'Bulgarian'
            ],
            [
                'value' => 'ca',
                'label' => 'Catalan'
            ],
            [
                'value' => 'zh-HK',
                'label' => 'Chinese (Hong Kong)'
            ],
            [
                'value' => 'zh-CN',
                'label' => 'Chinese (Simplified)'
            ],
            [
                'value' => 'zh-TW',
                'label' => 'Chinese (Traditional)'
            ],
            [
                'value' => 'hr',
                'label' => 'Croatian'
            ],
            [
                'value' => 'cs',
                'label' => 'Czech'
            ],
            [
                'value' => 'da',
                'label' => 'Danish'
            ],
            [
                'value' => 'nl',
                'label' => 'Dutch'
            ],
            [
                'value' => 'en-GB',
                'label' => 'English (UK)'
            ],
            [
                'value' => 'en',
                'label' => 'English (US)'
            ],
            [
                'value' => 'et',
                'label' => 'Estonian'
            ],
            [
                'value' => 'fil',
                'label' => 'Filipino'
            ],
            [
                'value' => 'fi',
                'label' => 'Finnish'
            ],
            [
                'value' => 'fr',
                'label' => 'French'
            ],
            [
                'value' => 'fr-CA',
                'label' => 'French (Canadian)'
            ],
            [
                'value' => 'gl',
                'label' => 'Galician'
            ],
            [
                'value' => 'ka',
                'label' => 'Georgian'
            ],
            [
                'value' => 'de',
                'label' => 'German'
            ],
            [
                'value' => 'de-AT',
                'label' => 'German (Austria)'
            ],
            [
                'value' => 'de-CH',
                'label' => 'German (Switzerland)'
            ],
            [
                'value' => 'el',
                'label' => 'Greek'
            ],
            [
                'value' => 'gu',
                'label' => 'Gujarati'
            ],
            [
                'value' => 'iw',
                'label' => 'Hebrew'
            ],
            [
                'value' => 'hi',
                'label' => 'Hindi'
            ],
            [
                'value' => 'hu',
                'label' => 'Hungarain'
            ],
            [
                'value' => 'is',
                'label' => 'Icelandic'
            ],
            [
                'value' => 'id',
                'label' => 'Indonesian'
            ],
            [
                'value' => 'it',
                'label' => 'Italian'
            ],
            [
                'value' => 'ja',
                'label' => 'Japanese'
            ],
            [
                'value' => 'kn',
                'label' => 'Kannada'
            ],
            [
                'value' => 'ko',
                'label' => 'Korean'
            ],
            [
                'value' => 'lo',
                'label' => 'Laothian'
            ],
            [
                'value' => 'lv',
                'label' => 'Latvian'
            ],
            [
                'value' => 'lt',
                'label' => 'Lithuanian'
            ],
            [
                'value' => 'ms',
                'label' => 'Malay'
            ],
            [
                'value' => 'ml',
                'label' => 'Malayalam'
            ],
            [
                'value' => 'mr',
                'label' => 'Marathi'
            ],
            [
                'value' => 'mn',
                'label' => 'Mongolian'
            ],
            [
                'value' => 'no',
                'label' => 'Norwegian'
            ],
            [
                'value' => 'fa',
                'label' => 'Persian'
            ],
            [
                'value' => 'pl',
                'label' => 'Polish'
            ],
            [
                'value' => 'pt',
                'label' => 'Portuguese'
            ],
            [
                'value' => 'pt-BR',
                'label' => 'Portuguese (Brazil)'
            ],
            [
                'value' => 'pt-PT',
                'label' => 'Portuguese (Portugal)'
            ],
            [
                'value' => 'ro',
                'label' => 'Romanian'
            ],
            [
                'value' => 'ru',
                'label' => 'Russian'
            ],
            [
                'value' => 'sr',
                'label' => 'Serbian'
            ],
            [
                'value' => 'si',
                'label' => 'Sinhalese'
            ],
            [
                'value' => 'sk',
                'label' => 'Slovak'
            ],
            [
                'value' => 'sl',
                'label' => 'Slovenian'
            ],
            [
                'value' => 'es',
                'label' => 'Spanish'
            ],
            [
                'value' => 'es-419',
                'label' => 'Spanish (Latin America)'
            ],
            [
                'value' => 'sw',
                'label' => 'Swahili'
            ],
            [
                'value' => 'sv',
                'label' => 'Swedish'
            ],
            [
                'value' => 'ta',
                'label' => 'Tamil'
            ],
            [
                'value' => 'te',
                'label' => 'Telugu'
            ],
            [
                'value' => 'th',
                'label' => 'Thai'
            ],
            [
                'value' => 'tr',
                'label' => 'Turkish'
            ],
            [
                'value' => 'uk',
                'label' => 'Ukrainian'
            ],
            [
                'value' => 'ur',
                'label' => 'Urdu'
            ],
            [
                'value' => 'vi',
                'label' => 'Vietnamese'
            ],
            [
                'value' => 'zu',
                'label' => 'Zulu'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getThemeOptions()
    {
        return [
            [
                'label' => __('Light Color Scheme'),
                'value' => 'light'
            ],
            [
                'label' => __('Dark Color Scheme'),
                'value' => 'dark'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getSizeOptions()
    {
        return [
            [
                'label' => __('Compact'),
                'value' => 'compact'
            ],
            [
                'label' => __('Normal'),
                'value' => 'normal'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getDefaultValues()
    {
		$default['label'] = 'Are you a robot?';
        return $default;
    }
}