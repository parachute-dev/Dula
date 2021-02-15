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

namespace BlueFormBuilder\Core\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\App\Filesystem\DirectoryList;

class File extends AbstractModel
{
    const UPLOAD_TMP = 'blueformbuilder/submission/tmp';
    const UPLOAD_FOLDER = 'blueformbuilder/submission';

    /**
     * File page cache tag
     */
    const CACHE_TAG = 'blueformbuilder_file';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'blueformbuilder_file';

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @param \Magento\Framework\Model\Context                             $context
     * @param \Magento\Framework\Registry                                  $registry
     * @param \Magento\Framework\Filesystem                                $filesystem
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
        $this->filesystem = $filesystem;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\BlueFormBuilder\Core\Model\ResourceModel\File::class);
    }

    public function getFileAbsolutePath()
    {
        $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $path = $mediaDirectory->getAbsolutePath(static::UPLOAD_FOLDER . $this->getData('file'));
        return $path;
    }
}
