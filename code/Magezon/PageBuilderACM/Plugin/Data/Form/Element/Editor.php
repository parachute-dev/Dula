<?php
namespace Magezon\PageBuilderACM\Plugin\Data\Form\Element;

class Editor
{
	/**
	 * @var \Magento\Framework\Registry
	 */
	protected $_registry;

	/**
	 * @var \Magento\Framework\View\LayoutFactory
	 */
	protected $layoutFactory;

	/**
	 * @param \Magento\Framework\Registry           $registry      
	 * @param \Magento\Framework\View\LayoutFactory $layoutFactory 
	 */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\LayoutFactory $layoutFactory
    ) {
		$this->_registry     = $registry;
		$this->layoutFactory = $layoutFactory;
    }

    public function afterGetElementHtml(
    	$subject,
    	$result
    ) {
    	if ($this->_registry->registry('current_content')) {
    		$regex  = '@(?:<textarea)(.*)</textarea>@msU';
        	preg_match_all($regex, $result, $_matches);
        	if (count($_matches[0])) {
        		$result = $_matches[0][0];
        		$id = time() . uniqid();
        		$block = $this->layoutFactory->create()->createBlock(\Magezon\PageBuilder\Block\Builder::class);
        		$data['html_id'] = 'magezon' . $id;
        		$data['target_id'] = $subject->getHtmlId();
        		$block->setData($data);
        		$result .= $block->toHtml();
        	}
    	}
    	return $result;
    }
}