<?php
/**
 * @category   Mageants Shopbylook
 * @package    Mageants_Shopbylook
 * @copyright  Copyright (c) 2017 Mageants
 * @author     Mageants Team <support@Mageants.com>
 */
namespace Mageants\SimpleConfigurable\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    private $actionFactory;

    /**
     * Event manager
     *
     * @var \Magento\Framework\Event\ManagerInterface
     */
    private $eventManager;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    private $response;

    private $productCollection;

    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection
    ) {
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
        $this->storeManager = $storeManager;
        $this->response = $response;
        $this->productCollection = $productCollection;
    }

    /**
     * Validate and Match Shopbylook Pages and modify request
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $curl = $request->getUri();
        $curl = explode('/', $curl);
        $curl = end($curl);

        if (strpos($curl, '.html') !== false) {
            $curl = str_replace('.html', '', $curl);
        }

        if ($curl == "") {
            return false;
        }

        /** Apply filters here */
        $collection = $this->productCollection
            ->addAttributeToSelect('*')->addAttributeToFilter('url_key', $curl)->load();

        foreach ($collection as $product) {
            if ($product->getId()) {
                $request->setModuleName('catalog')
                   ->setControllerName('product')
                   ->setActionName('view')
                   ->setParam('id', $product->getId());
                   return;
            } else {
                return false;
            }
        }

        return false;
    }
}
