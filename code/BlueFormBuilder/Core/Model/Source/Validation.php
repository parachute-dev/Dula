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

namespace BlueFormBuilder\Core\Model\Source;

class Validation implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $validations = [
            ''                              => 'Disable',
            'validate-no-html-tags'         => __('HTML tags are not allowed.'),
            'validate-select'               => __('Please select an option.'),
            'validate-no-empty'             => __('Empty Value.'),
            'validate-alphanum-with-spaces' => __('Please use only letters (a-z or A-Z), numbers (0-9) or spaces only in this field.'),
            'validate-data'                 => __('Please use only letters (a-z or A-Z), numbers (0-9) or underscore (_) in this field, and the first character should be a letter.'),
            'validate-street'               => __('Please use only letters (a-z or A-Z), numbers (0-9), spaces and "#" in this field.'),
            'validate-phoneStrict'          => __('Please enter a valid phone number. For example (123) 456-7890 or 123-456-7890.'),
            'validate-phoneLax'             => __('Please enter a valid phone number. For example (123) 456-7890 or 123-456-7890.'),
            'validate-fax'                  => __('Please enter a valid fax number (Ex: 123-456-7890).'),
            'validate-email'                => __('Please enter a valid email address (Ex: johndoe@domain.com).'),
            'validate-password'             => __('Please enter 6 or more characters. Leading and trailing spaces will be ignored.'),
            'validate-admin-password'       => __('Please enter 7 or more characters, using both numeric and alphabetic.'),
            'validate-url'                  => __('Please enter a valid URL. Protocol is required (http://, https:// or ftp://).'),
            'validate-clean-url'            => __('Please enter a valid URL. For example http://www.example.com or www.example.com.'),
            'validate-xml-identifier'       => __('Please enter a valid XML-identifier (Ex: something_1, block5, id-4).'),
            'validate-ssn'                  => __('Please enter a valid social security number (Ex: 123-45-6789).'),
            'validate-zip-us'               => __('Please enter a valid zip code (Ex: 90602 or 90602-1234).'),
            'validate-date-au'              => __('Please use this date format: dd/mm/yyyy. For example 17/03/2006 for the 17th of March, 2006.'),
            'validate-currency-dollar'      => __('Please enter a valid $ amount. For example $100.00.'),
            'validate-not-negative-number'  => __('Please enter a number 0 or greater in this field.'),
            'validate-zero-or-greater'      => __('Please enter a number 0 or greater in this field.'),
            'validate-greater-than-zero'    => __('Please enter a number greater than 0 in this field.'),
            'validate-css-length'           => __('Please input a valid CSS-length (Ex: 100px, 77pt, 20em, .5ex or 50%).'),
            'validate-number'               => __('Please enter a valid number in this field.'),
            'validate-integer'              => __('Please enter a valid integer in this field.'),
            'validate-number-range'         => __('The value is not within the specified range.'),
            'validate-digits'               => __('Please enter a valid number in this field.'),
            'validate-alpha'                => __('Please use letters only (a-z or A-Z) in this field.'),
            'validate-code'                 => __('Please use only letters (a-z), numbers (0-9) or underscore (_) in this field, and the first character should be a letter.'),
            'validate-alphanum'             => __('Please use only letters (a-z or A-Z) or numbers (0-9) in this field. No spaces or other characters are allowed.'),
            'validate-date'                 => __('Please enter a valid date.'),
            'validate-identifier'           => __('Please enter a valid URL Key (Ex: "example-page", "example-page.html" or "anotherlevel/example-page").'),
            'validate-zip-international'    => __('Please enter a valid zip code.'),
            'validate-state'                => __('Please select State/Province.'),
            'validate-emails'               => __('Please enter valid email addresses, separated by commas. For example, johndoe@domain.com, johnsmith@domain.com.'),
            'validate-cc-number'            => __('Please enter a valid credit card number.'),
            'validate-cc-ukss'              => __('Please enter issue number or start date for switch/solo card type.'),
            'validate-per-page-value-list'  => __('Please enter a valid value, ex: 10,20,30'),
            'validate-new-password'         => __('Please enter 6 or more characters. Leading and trailing spaces will be ignored.')
        ];

        $options = [];
        foreach ($validations as $k => $label) {
            $options[] = [
                'label' => $label,
                'value' => $k
            ];
        }
        return $options;
    }
}
