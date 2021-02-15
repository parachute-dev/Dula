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

namespace BlueFormBuilder\Core\Block\Adminhtml\Form\Submission\Field\Renderer;

use BlueFormBuilder\Core\Model\Form\Submission\Collection;

class StarRatings extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
	/**
	 * @var \Magento\Framework\Registry
	 */
	protected $registry;

	/**
	 * @param \Magento\Framework\Registry $registry
	 */
	public function __construct(
        \Magento\Framework\Registry $registry
	) {
		$this->registry = $registry;
	}

	/**
	 * @return \BlueFormBuilder\Core\Model\Form
	 */
	public function getCurrentForm()
	{
		return $this->registry->registry('current_form');
	}

    /**
     * Renders grid column
     *
     * @param   \Magento\Framework\DataObject $row
     * @return  string
     */
    public function render(\Magento\Framework\DataObject $row)
    {
		$value         = (int)$row->getData(Collection::PREFIX . $this->getColumn()->getIndex());
		$element       = $this->getCurrentForm()->getElement($this->getColumn()->getIndex(), 'elem_name');
		$numberOfStars = $element->getConfig('number_of_stars');
		$percent       = $value / $element->getConfig('number_of_stars') * 100;
		$result   = '<div class="bfb-rating-summary bfb-rating-summary-' . $numberOfStars . '">
	         <div class="rating-result" title="' . $this->_getValue($row) . '">
	             <span style="width:' . $percent . '%">
	                 <span>
	                     <span itemprop="ratingValue">' . $percent . '</span>% of <span itemprop="bestRating">100</span>
	                 </span>
	             </span>
	         </div>
	    </div><br/>';
		return $result;
    }

    /**
     * Render column for export
     *
     * @param Object $row
     * @return string
     */
    public function renderExport(\Magento\Framework\DataObject $row)
    {
        return $row->getData($this->getColumn()->getIndex());
    }
}