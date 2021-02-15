<?php
/**
 * Blackbird ContentManager Module
 *
 *  NOTICE OF LICENSE
 *  If you did not receive a copy of the license and are unable to
 *  obtain it through the world-wide-web, please send an email
 *  to contact@bird.eu so we can send you a copy immediately.
 *
 * @category        Blackbird
 * @package         Blackbird_ContentManager
 * @copyright       Copyright (c) 2018 Blackbird (https://black.bird.eu)
 * @author          Blackbird Team
 * @license         https://www.advancedcontentmanager.com/license/
 */

namespace Blackbird\ContentManager\Block\Adminhtml\Content\Edit\Tab\Form\Type\RepeaterFields;

use Blackbird\ContentManager\Api\Data\ContentInterface;
use Blackbird\ContentManager\Api\Data\ContentType\CustomFieldInterface;
use Blackbird\ContentManager\Api\Data\ContentTypeInterface;
use Blackbird\ContentManager\Model\Content;
use Blackbird\ContentManager\Model\ContentType\CustomField;
use Blackbird\ContentManager\Model\ContentType\CustomFieldset;
use Blackbird\ContentManager\Model\ResourceModel\ContentType\CustomField\Option\Collection as OptionCollection;
use Magento\Framework\Data\Form;
use Magento\Store\Model\Store;

/**
 * Class RepeaterFields
 *
 * @package Blackbird\ContentManager\Block\Adminhtml\Content\Edit\Tab\Form\Type\RepeaterFields
 */
class RepeaterFields extends \Blackbird\ContentManager\Block\Adminhtml\Content\Edit\Tab\Form\Type\AbstractType
{
    /**
     * @var \Magento\Framework\Data\FormFactory
     */
    protected $_formFactory;

    /**
     * @var \Magento\Framework\Data\Form\Element\Factory
     */
    protected $_factoryElement;

    /**
     * @var \Blackbird\ContentManager\Model\Config\Source\ContentType\CustomFields\Type
     */
    protected $_fieldTypeSource;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    /**
     * @var \Magento\Config\Model\Config\Source\Enabledisable
     */
    protected $_enabledisable;

    /**
     * @var \Magento\Directory\Model\Config\Source\Country
     */
    protected $_country;

    /**
     * @var \Magento\Config\Model\Config\Source\Locale\Currency
     */
    protected $_currency;

    /**
     * @var \Magento\Config\Model\Config\Source\Locale
     */
    protected $_locale;

    /**
     * @var \Blackbird\ContentManager\Model\ResourceModel\ContentType\CollectionFactory
     */
    protected $_contentTypeCollectionFactory;

    /**
     * @var string
     */
    protected $_template = 'Blackbird_ContentManager::content/edit/tab/form/type/repeaterfields/repeaterfields.phtml';

    /**
     * @var \Blackbird\ContentManager\Model\ResourceModel\Content\CollectionFactory
     */
    protected $_contentCollectionFactory;

    /**
     * @var \Blackbird\ContentManager\Model\ResourceModel\Content\Collection
     */
    protected $contentCollectionRepeaters;

    private $repeaterSpecificFieldType = '';

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Framework\Data\Form\Element\Factory $factoryElement
     * @param \Blackbird\ContentManager\Model\Config\Source\ContentType\CustomFields\Type $fieldTypeSource
     * @param \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
     * @param \Magento\Config\Model\Config\Source\Enabledisable $enabledisable
     * @param \Magento\Directory\Model\Config\Source\Country $country
     * @param \Magento\Config\Model\Config\Source\Locale\Currency $currency
     * @param \Magento\Config\Model\Config\Source\Locale $locale
     * @param \Blackbird\ContentManager\Model\ResourceModel\ContentType\CollectionFactory $contentTypeCollectionFactory
     * @param \Blackbird\ContentManager\Model\ResourceModel\Content\CollectionFactory $contentCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Blackbird\ContentManager\Model\Config\Source\ContentType\CustomFields\Type $fieldTypeSource,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        \Magento\Config\Model\Config\Source\Enabledisable $enabledisable,
        \Magento\Directory\Model\Config\Source\Country $country,
        \Magento\Config\Model\Config\Source\Locale\Currency $currency,
        \Magento\Config\Model\Config\Source\Locale $locale,
        \Blackbird\ContentManager\Model\ResourceModel\ContentType\CollectionFactory $contentTypeCollectionFactory,
        \Blackbird\ContentManager\Model\ResourceModel\Content\CollectionFactory $contentCollectionFactory,
        array $data = []
    ) {
        $this->_formFactory = $formFactory;
        $this->_factoryElement = $factoryElement;
        $this->_fieldTypeSource = $fieldTypeSource;
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_enabledisable = $enabledisable;
        $this->_country = $country; // todo improve
        $this->_currency = $currency; // todo improve
        $this->_locale = $locale; // todo improve
        $this->_contentTypeCollectionFactory = $contentTypeCollectionFactory;
        $this->_contentCollectionFactory = $contentCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldType()
    {
        return 'repeater_fields';
    }

    /**
     * @return string
     */
    public function getRepeaterSpecificFieldType()
    {
        return $this->repeaterSpecificFieldType;
    }

    /**
     * @param $repeaterSpecificFieldType
     * @return string
     */
    public function setRepeaterSpecificFieldType($repeaterSpecificFieldType)
    {
        $this->repeaterSpecificFieldType = $repeaterSpecificFieldType;

        return $this->repeaterSpecificFieldType;
    }

    /**
     * Get the repeaters that were already saved for this content
     *
     * @param $index
     * @param $elementIdentifier
     * @param $idRepeater
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getSavedRepeater($index, $elementIdentifier, $idRepeater)
    {
        return $this->getFormHtml($index, $elementIdentifier, $idRepeater);
    }

    /**
     * @param $indexId
     * @param $elementIdentifier
     * @param null $idRepeater
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormHtml($indexId, $elementIdentifier, $idRepeater = null)
    {
        $html = '';

        /** @var \Blackbird\ContentManager\Model\ContentType\CustomFieldset $customFieldset */
        foreach ($this->getRepeaterField()->getCustomFieldsetCollection() as $customFieldset) {
            /** @var \Blackbird\ContentManager\Model\ContentType\CustomField $customField */
            $form = $this->_formFactory->create();
            $content = null;
            if ($idRepeater) {
                $content = $this->getRepeaterField()
                    ->getContentCollection()
                    ->addAttributeToFilter(ContentInterface::ID, $idRepeater)
                    ->addAttributeToSelect('*')
                    ->getFirstItem();
            }

            $html .= $this->createElementHml($form, $customFieldset, $indexId, $elementIdentifier, $idRepeater,
                $content);
        }


        return $html;
    }

    /**
     * Retrieve the content type of the repeater field
     *
     * @return \Blackbird\ContentManager\Model\ContentType
     */
    public function getRepeaterField()
    {
        if (!$this->hasData('repeater_field')) {
            $contentType = $this->_contentTypeCollectionFactory->create()
                ->addFieldToSelect([ContentTypeInterface::ID])
                ->addFieldToFilter(ContentTypeInterface::IDENTIFIER, $this->getCustomField()->getData('content_type'));

            if ($contentType->count()) {
                $this->setData('repeater_field', $contentType->getFirstItem());
            }
        }

        return $this->getData('repeater_field');
    }

    /**
     * Return frontend renderer type corresponding to contenttype type
     * For render in FORM (when creating new content)
     *
     * @param string $fieldType
     * @return string
     */
    public function getRendererTypeByFieldType($fieldType)
    {
        //Set the type of the field
        $this->setRepeaterSpecificFieldType($fieldType);

        $fieldTypeToDataType = [
            'field' => 'text',
            'area' => 'textarea',
            'editor' => 'editor',
            'password' => 'password',
            'file' => 'file',
            'image' => 'image',
            'drop_down' => 'select',
            'radio' => 'radios',
            'checkbox' => 'checkboxes',
            'multiple' => 'multiselect',
            'date' => 'date',
            'date_time' => 'date',
            'time' => 'time',
            'int' => 'text',
            'country' => 'select',
            'currency' => 'select',
            'locale' => 'select',
            // Special type for category field
            'category' => '\Blackbird\ContentManager\Block\Adminhtml\Category\Widget\Chooser',
        ];

        return isset($fieldTypeToDataType[$fieldType]) ? $fieldTypeToDataType[$fieldType] : 'text';
    }

    /**
     * @param Form $form
     * @param CustomFieldset $customFieldset
     * @param $indexId
     * @param $elementIdentifier
     * @param null $idRepeater
     * @param Content|null $content
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createElementHml(
        Form $form,
        CustomFieldset $customFieldset,
        $indexId,
        $elementIdentifier,
        $idRepeater = null,
        Content $content = null
    ) {
        foreach ($customFieldset->getCustomFieldCollection() as $customField) {
            //TODO : Warning customfield is updated by reference in this function
            $config = $this->getCustomFieldConfiguration($customField, $indexId, $elementIdentifier, $idRepeater);

            $element = $this->_factoryElement->create($this->getRendererTypeByFieldType($customField->getType()),
                ['data' => $config]);

            //        $element->setForm($form);
            $element->setId($customField->getIdentifier());

            // Prepare the renderer if it exists
            $renderer = $this->getCustomFieldTypeRenderer($customField->getType());

            if (!empty($renderer)) {
                $element->setRenderer($this->getLayout()
                    ->createBlock($renderer, '',
                        $this->_prepareDataBlock($customField, $content, $indexId, $elementIdentifier)));
            }

            /** If this repeater field was already saved  */
            $contentRepeater = false;
            if ($idRepeater != null) {
                $contentRepeater = $this->contentCollectionRepeaters->getItemById($idRepeater);
            }

            $form->addElement($element);
            $moreInfo = '';
            $element->setData('is_in_repeater', 1);

            //Can add some stuff depending on customField type
            if ($customField->getType() === CustomFieldInterface::TYPE_IMAGE) {
                if ($customField->getData(CustomField::IMG_TITLE)) {
                    $configTitleImage = [
                        'id' => $customField->getIdentifier() . '_titl'.$indexId,
                        'name' => 'repeater-field' . '[' . $elementIdentifier . ']'  . '[' . $indexId . ']' . '[' .$customField->getIdentifier() . '_titl' . ']',
                        'label' => __('%1 Attribute Title', $customField->getTitle()),
                        'title' => __('Image Attribute Title'),
                        'required' => $customField->getIsRequire(),
                    ];
                    if($contentRepeater) {
                        $value = $contentRepeater->getData($customField->getIdentifier() . '_titl');
                        $configTitleImage['value'] = $value;
                    }

                    $imgTitle = $this->_factoryElement->create($this->getRendererTypeByFieldType('field'),
                        ['data' => $configTitleImage]);
                    $imgTitle->setForm($form);
                    $imgTitle->setId($configTitleImage['id']);
                    $form->addElement($imgTitle);
                }
                if ($customField->getData(CustomField::IMG_ALT)) {
                    $configAltImage = [
                        'id' => $customField->getIdentifier() . '_alt' . $indexId,
                        'name' => 'repeater-field' . '[' . $elementIdentifier . ']'  . '[' . $indexId . ']' . '[' .$customField->getIdentifier() . '_alt' . ']',
                        'label' => __('%1 Attribute Alt', $customField->getTitle()),
                        'title' => __('Image Attribute Alt'),
                        'required' => $customField->getIsRequire(),
                    ];
                    if($contentRepeater) {
                        $value = $contentRepeater->getData($customField->getIdentifier() . '_alt');
                        $configAltImage['value'] = $value;
                    }

                    $imgAlt = $this->_factoryElement->create($this->getRendererTypeByFieldType('field'),
                        ['data' => $configAltImage]);
                    $imgAlt->setForm($form);
                    $imgAlt->setId($configAltImage['id']);
                    $form->addElement($imgAlt);
                }
                if ($customField->getData(CustomField::IMG_URL)) {
                    $configUrlImage = [
                        'id' => $customField->getIdentifier().'_url'.$indexId,
                        'name' => 'repeater-field' . '[' . $elementIdentifier . ']'  . '[' . $indexId . ']' . '[' .$customField->getIdentifier() . '_url' . ']',
                        'label' => __('%1 Attribute Url', $customField->getTitle()),
                        'title' => __('Image Attribute Url'),
                        'required' => $customField->getIsRequire(),
                    ];
                    if($contentRepeater) {
                        $value = $contentRepeater->getData($customField->getIdentifier() . '_url');
                        $configUrlImage['value'] = $value;
                    }

                    $imgUrl = $this->_factoryElement->create($this->getRendererTypeByFieldType('field'),
                        ['data' => $configUrlImage]);
                    $imgUrl->setForm($form);
                    $imgUrl->setId($configUrlImage['id']);
                    $form->addElement($imgUrl);
                }
            } elseif ($customField->getType() === 'editor') {
                //To fix wysiwyg for area
                $element->setData('html_id', $elementIdentifier . $indexId. '_' . $customField->getIdentifier() .'_field');
            }
            elseif ($customField->getType() === 'date' || $customField->getType() === 'date_time') {
                //To fix date and datetime field within repeater
                $element->setData('html_id', $elementIdentifier . $indexId. '_' . $customField->getIdentifier() .'_field');
            }
        }

        return $form->toHtml();
    }

    /**
     * Build the custom field configuration
     *
     * @param \Blackbird\ContentManager\Model\ContentType\CustomField $customField
     * @param $indexId
     * @param $elementIdentifier
     * @param null $idRepeater
     * @return array
     */
    protected function getCustomFieldConfiguration(
        CustomField $customField,
        $indexId,
        $elementIdentifier,
        $idRepeater = null
    ) {
        $config = [
            'name' => 'repeater-field'.'['.$elementIdentifier.']'.'['.$indexId.']'.'['.$customField->getIdentifier().']',
            'label' => $customField->getTitle(),
            'title' => $customField->getTitle().'-'.$indexId,
            'required' => $customField->getIsRequire(),
            'note' => $customField->getNote(),
        ];

        /** If this repeater field was already saved  */

        if ($idRepeater != null) {
            $contentRepeater = $this->contentCollectionRepeaters->getItemById($idRepeater);
            $value = $contentRepeater->getData($customField->getIdentifier());
            $config['value'] = $value;
        }

        // Build the field configuration
        switch ($customField->getType()) {
            case CustomFieldInterface::TYPE_AREA:
                if ($customField->getWysiwygEditor()) {
                    $customField->setType('editor');
                    $config['config'] = $this->_wysiwygConfig->getConfig(['add_directives' => true]);
                }
                $config = array_merge($config, $this->getMaxLengthConfig($customField->getMaxCharacters()));
                break;
            case CustomFieldInterface::TYPE_DATE:
                $config = array_merge($config, $this->getDateConfig());
                break;
            case CustomFieldInterface::TYPE_DATE_TIME:
                $config = array_merge($config, $this->getDatetimeConfig());
                break;
            case CustomFieldInterface::TYPE_TIME:
                $config = array_merge($config, $this->getTimeConfig());
                break;
            case CustomFieldInterface::TYPE_RADIO:
            case CustomFieldInterface::TYPE_DROP_DOWN:
            case CustomFieldInterface::TYPE_MULTIPLE:
                $config['values'] = $this->getValuesConfig($customField->getOptionCollection());
                break;
            case CustomFieldInterface::TYPE_CHECKBOX:
                $config['name'] = $config['name'].'[]';
                $config['values'] = $this->getValuesConfig($customField->getOptionCollection());
                break;
            case CustomFieldInterface::TYPE_IMAGE:
                unset($config['require']);
                $config['identifier_image_field'] = 'repeater-field'.'['.$elementIdentifier.']'.'['.$indexId.']'.'['.'content_image'.']'.'['.$customField->getIdentifier().']';
                $config['dropzone_name'] = 'dropzone_repeater_field_'.$elementIdentifier.'_'.$indexId.'_'.$customField->getIdentifier();
                break;
            case CustomFieldInterface::TYPE_COUNTRY:
                $config['values'] = $this->_country->toOptionArray();
                break;
            case CustomFieldInterface::TYPE_CURRENCY:
                $config['values'] = $this->_currency->toOptionArray();
                break;
            case CustomFieldInterface::TYPE_LOCALE:
                $config['values'] = $this->_locale->toOptionArray();
                break;
            case CustomFieldInterface::TYPE_FILE:
                $config['delete_input_name'] = 'delete-repeater-file['.$indexId.']';
                $config['delete_name'] = $customField->getIdentifier(); // To know if the file is in a repeater field or not.
                break;
            case CustomFieldInterface::TYPE_PRODUCT:
            case CustomFieldInterface::TYPE_CATEGORY:
            case CustomFieldInterface::TYPE_CUSTOMER:
            case CustomFieldInterface::TYPE_CONTENT:
            case CustomFieldInterface::TYPE_CONTENT_LIST:
            case CustomFieldInterface::TYPE_ATTRIBUTE:
                $config['identifier_field'] = $elementIdentifier.$customField->getIdentifier().$indexId.'_field';
                break;
            // Text field by default
            default:
                if ($customField->getMaxCharacters()) {
                    $config = array_merge($config, $this->getMaxLengthConfig($customField->getMaxCharacters()));
                }
        }

        return $config;
    }

    /**
     * @param string $maxlength
     * @return array
     */
    protected function getMaxLengthConfig($maxlength = null)
    {
        $config = [];

        if (!empty($maxlength)) {
            $config['maxlength'] = $maxlength;
            if (!isset($config['class'])) {
                $config['class'] = '';
            }
            $config['class'] .= ' validate-length maximum-length-'.$maxlength;
        }

        return $config;
    }

    /**
     * @return array
     */
    protected function getDateConfig()
    {
        return [
            'input_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
            'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
            'change_month' => 'true',
            'change_year' => 'true',
            'show_on' => 'both',
            'image' => $this->getViewFileUrl('Magento_Theme::calendar.png'),
        ];
    }

    /**
     * @return array
     */
    protected function getDatetimeConfig()
    {
        return [
            'input_format' => \Magento\Framework\Stdlib\DateTime::DATETIME_INTERNAL_FORMAT,
            'date_format' => $this->_localeDate->getDateFormat(\IntlDateFormatter::SHORT),
            'time_format' => $this->_localeDate->getTimeFormat(\IntlDateFormatter::SHORT),
            'change_month' => 'true',
            'change_year' => 'true',
            'show_on' => 'both',
            'image' => $this->getViewFileUrl('Magento_Theme::calendar.png'),
        ];
    }

    /**
     * Build the option array for the config array
     *
     * @param OptionCollection $options'
     * @return array
     */
    protected function getValuesConfig(OptionCollection $options)
    {
        $valuesConfig = [];

        foreach ($options as $option) {
            $valuesConfig[] = [
                'label' => $option->getTitle(),
                'value' => empty($option->getValue()) ? $option->getTitle() : $option->getValue(),
            ];
        }

        return $valuesConfig;
    }

    /**
     * Retrieve renderer for special custom field type
     *
     * @param string $fieldType
     * @return string
     */
    protected function getCustomFieldTypeRenderer($fieldType)
    {
        $renders = $this->_fieldTypeSource->getCustomFieldsTypesRenderer();

        return (isset($renders[$fieldType])) ? $renders[$fieldType] : null;
    }

    /**
     * Build an array of data for the block
     *
     * @param CustomField $customField
     * @param Content $content
     * @return array
     */
    protected function _prepareDataBlock(
        CustomField $customField,
        Content $content = null
    ) {
        $data = ['data' => []];

        $data['data']['custom_field'] = $customField;

        if (!empty($content)) {
            $data['data']['content_field'] = [
                $customField->getIdentifier() => $content->getData($customField->getIdentifier()),
            ];

            // Add specific data when the field is type of image
            if ($customField->getType() === CustomFieldInterface::TYPE_IMAGE) {
                $key = $customField->getIdentifier().'_orig';
                $data['data']['content_field'][$key] = $content->getData($key);
                $key = $customField->getIdentifier().'_titl';
                $data['data']['content_field'][$key] = $content->getData($key);
                $key = $customField->getIdentifier().'_url';
                $data['data']['content_field'][$key] = $content->getData($key);
                $key = $customField->getIdentifier().'_alt';
                $data['data']['content_field'][$key] = $content->getData($key);
            }
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();

        $idsRepeater = explode(',', $this->getElement()->getValue());

        $this->contentCollectionRepeaters = $this->_contentCollectionFactory->create()
            ->addStoreFilter($this->getRequest()->getParam('store', Store::DEFAULT_STORE_ID))
            ->addAttributeToFilter(Content::ID, [$idsRepeater])
            ->addAttributeToSelect(['*']);

        return $this;
    }
}
