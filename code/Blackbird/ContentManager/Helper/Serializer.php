<?php
/**
 * Serializer
 *
 * @copyright Copyright © 2019 Blackbird. All rights reserved.
 * @author    etienne (Blackbird Team)
 */
declare(strict_types=1);

namespace Blackbird\ContentManager\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Serialize as MagentoSerialize;
use Magento\Framework\App\Helper\AbstractHelper;

class Serializer extends AbstractHelper
{
    /**
     * @var MagentoSerialize
     */
    private $serializer;

    /**
     * Serializer constructor.
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\Serialize\Serializer\Serialize $serializer
     */
    public function __construct(
        Context $context,
        MagentoSerialize $serializer
    )
    {
        parent::__construct($context);
        $this->serializer = $serializer;
    }

    /**
     * @param $data
     * @return bool|string
     */
    public function serialize($data)
    {
       return $this->serializer->serialize($data);
    }

    /**
     * @param $string
     * @return array|bool|float|int|mixed|null|string
     */
    public function unserialize($string)
    {
        return $this->serializer->unserialize($string);
    }
}