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

namespace BlueFormBuilder\Core\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const OPERATOR_EQUALS_TO        = 'eq';
    const OPERATOR_NOT_EQUALS_TO    = 'neq';
    const OPERATOR_GREATER_THAN     = 'gt';
    const OPERATOR_LESS_THAN        = 'lt';
    const OPERATOR_CONTAINS         = 'ct';
    const OPERATOR_DOES_NOT_CONTAIN = 'dct';
    const OPERATOR_STARTS_WIDTH     = 'sw';
    const OPERATOR_ENDS_WIDTH       = 'ew';
    const OPERATOR_EMPTY            = 'et';
    const OPERATOR_NOT_EMPTY        = 'net';
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;

    /**
     * @var \Magento\Framework\View\LayoutInterface
     */
    protected $layout;

    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filterManager;

    /**
     * @param \Magento\Framework\App\Helper\Context      $context        
     * @param \Magento\Store\Model\StoreManagerInterface $_storeManager  
     * @param \Magento\Cms\Model\Template\FilterProvider $filterProvider 
     * @param \Magento\Framework\View\LayoutInterface    $layout         
     * @param \Magento\Framework\Filter\FilterManager    $filterManager  
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $_storeManager,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\Filter\FilterManager $filterManager
    ) {
        parent::__construct($context);
        $this->_storeManager   = $_storeManager;
        $this->_filterProvider = $filterProvider;
        $this->layout          = $layout;
        $this->filterManager   = $filterManager;
    }
   
    /**
     * @param  string $key
     * @param  null|int $store
     * @return null|string
     */
    public function getConfig($key, $store = null)
    {
        $store     = $this->_storeManager->getStore($store);
        $websiteId = $store->getWebsiteId();
        $result    = $this->scopeConfig->getValue(
            'blueformbuilder/' . $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $store
        );
        return $result;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->getConfig('general/route');
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->getConfig('general/enabled');
    }

    /**
     * @param  string $str 
     * @return string      
     */
    public function filter($str)
    {
        if (!$str) return;
        $storeId = $this->_storeManager->getStore()->getId();
        $html    = $this->_filterProvider->getBlockFilter()->setStoreId($storeId)->filter($str);
        $matches1 = $matches2 = $matches3 = [];
        preg_match_all( '/\[customer\..*?]/is', $html, $matches1 );
        preg_match_all( '/\[page\..*?]/is', $html, $matches2 );
        preg_match_all( '/\[product\..*?]/is', $html, $matches3 );
        $matches = array_merge($matches1[0], $matches2[0], $matches3[0]);
        $replace = $search = [];
        foreach ($matches as $match) {
            $search[]  = $match;
            $replace[] = '<span class="mgz-hidden bfb-dynamic-variables">' . $match . '</span>';
        }
        $html = str_replace( $search, $replace, $html );
        return $html;
    }

    /**
     * @return boolean
     */
    public function startsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @return boolean
     */
    public function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        return $length === 0 ||
        (substr($haystack, -$length) === $needle);
    }

    /**
     * Get full form url
     *
     * @return string
     */
    public function getFormUrl($identifier)
    {
        $url   = $this->_storeManager->getStore()->getBaseUrl();
        $route = $this->getRoute() . '/';
        return $url . $route . $identifier;
    }

    /**
     * @param  \BlueFormBuilder\Core\Model\Submssion $submission 
     * @return string
     */
    public function getMarkUnreadUrl($submission)
    {
        $url = $this->_urlBuilder->getUrl('blueformbuilder/submission/markAsUnread', [
            'id'  => $submission->getId(),
            'key' => $submission->getSubmissionHash()
        ]);
        return $url;
    }

    /**
     * @param  string $code
     * @return string
     */
    public function renderForm($code)
    {
        return $this->layout->createBlock('\BlueFormBuilder\Core\Block\Form')->setCode($code)->toHtml();
    }

    /**
     * @return array
     */
    public function getOperatorOptions()
    {
        return [
            [
                'label' => __('is equal to'),
                'value' => static::OPERATOR_EQUALS_TO
            ],
            [
                'label' => __('is not equal to'),
                'value' => static::OPERATOR_NOT_EQUALS_TO
            ],
            [
                'label' => __('is greater than'),
                'value' => static::OPERATOR_GREATER_THAN
            ],
            [
                'label' => __('is less than'),
                'value' => static::OPERATOR_LESS_THAN
            ],
            [
                'label' => __('contains'),
                'value' => static::OPERATOR_CONTAINS
            ],
            [
                'label' => __('does not contain'),
                'value' => static::OPERATOR_DOES_NOT_CONTAIN
            ],
            [
                'label' => __('starts with'),
                'value' => static::OPERATOR_STARTS_WIDTH
            ],
            [
                'label' => __('ends with'),
                'value' => static::OPERATOR_ENDS_WIDTH
            ],
            [
                'label' => __('is empty'),
                'value' => static::OPERATOR_EMPTY
            ],
            [
                'label' => __('is not empty'),
                'value' => static::OPERATOR_NOT_EMPTY
            ]
        ];
    }

    /**
     * @param  array $condition
     * @param  array $post
     * @return bool
     */
    public function validateCondition($form, $condition, $post)
    {
        $valid = false;

        foreach ($condition as $row) {
            $element = $form->getElement($row['field']);

            if (!$element || !isset($row['field'])) continue;

            $elemName = $element->getElemName();

            if (!isset($post[$elemName])) $post[$elemName] = '';

            $values = [];
            if (is_string($post[$elemName])) {
                $values[] = $post[$elemName];
            } else {
                $values = $post[$elemName];
            }

            $postValues = [];
            foreach ($values as $_value) {
                $postValues[] = strtolower(trim($_value));
            }

            if (isset($row['value'])) {
                $row['value'] = strtolower(trim($row['value']));    
            } else {
                $row['value'] = '';
            }
            

            switch ($row['operator']) {
                case 'eq':
                        $valid = in_array($row['value'], $postValues);
                    break;

                case 'neq':
                        $valid = !in_array($row['value'], $postValues);
                    break;

                case 'gt':
                        $total = 0;
                        foreach ($postValues as $_value) {
                            $total += (float) $_value;
                        }
                        $valid = $total > $row['value'];
                    break;

                case 'lt':
                        $total = 0;
                        foreach ($postValues as $_value) {
                            $total += (float) $_value;
                        }
                        $valid = $total < $row['value'];
                    break;

                case 'ct':
                        $str = '';
                        foreach ($postValues as $_value) {
                            $str .= $_value;
                        }
                        $valid = (strpos($str, $row['value']) !== false);
                    break;

                case 'dct':
                        $str = '';
                        foreach ($postValues as $_value) {
                            $str .= $_value;
                        }
                        $valid = (strpos($str, $row['value']) === false);
                    break;

                case 'sw':
                        $valid = false;
                        foreach ($postValues as $_value) {
                            if ($this->startsWith($_value, $row['value'])) {
                                $valid = true;
                                break;
                            }
                        }
                    break;

                case 'ew':
                        $valid = false;
                        foreach ($postValues as $_value) {
                            if ($this->endsWith($_value, $row['value'])) {
                                $valid = true;
                                break;
                            }
                        }
                    break;

                case 'et': 
                        $valid = (in_array('', $postValues) || in_array('0', $postValues));
                    break;

                case 'net': 
                        $valid = (!in_array('', $postValues) && !in_array('0', $postValues));
                    break;
            }

            if (!isset($row['aggregator'])) $row['aggregator'] = 'and';
            if ($row['aggregator'] === 'or' && $valid) {
                break;
            }

            if ($row['aggregator'] === 'and' && !$valid) {
                break;
            }
        }

        return $valid;
    }

    /**
     * Convert byte count to float KB/MB format
     *
     * @param int $bytes
     * @return string
     */
    public function byteconvert($bytes)
    {
        if (!$bytes) {
            return;
        }
        $symbol = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $exp = floor(log($bytes) / log(1024));
        return sprintf('%.2f ' . $symbol[$exp], $bytes / pow(1024, floor($exp)));
    }

    public function removeScript($string, $allowableTags = '<p> <b>', $allowHtmlEntities = null)
    {
        if (is_array($string)) {
            foreach ($string as &$row) {
                if (is_string($row)) {
                    $row = $this->_stripTags($row, $allowableTags, $allowHtmlEntities);
                }
            }
        } else {
            $string = $this->_stripTags($string, $allowableTags, $allowHtmlEntities);
        }
        return $string;
    }

    private function _stripTags($string, $allowableTags, $allowHtmlEntities)
    {
        $string = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $string);
        $string = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $string);
        // $string = $this->filterManager->stripTags(
        //     $string,
        //     ['allowableTags' => $allowableTags, 'escape' => $allowHtmlEntities]
        // );
        return $string;
    }
}
