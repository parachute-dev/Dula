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

namespace BlueFormBuilder\Core\Block\Element;

class File extends Control
{
    /**
     * @var \Magento\Downloadable\Helper\File
     */
    protected $_downloadableFile;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory
     */
    protected $fileCollectionFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context                 $context               
     * @param \Magento\Downloadable\Helper\File                                $downloadableFile      
     * @param \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory 
     * @param array                                                            $data                  
     */
    public function __construct(
         \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Downloadable\Helper\File $downloadableFile,
        \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_downloadableFile     = $downloadableFile;
        $this->fileCollectionFactory = $fileCollectionFactory;
    }

    /**
     * @param  int $submissionId
     * @return \BlueFormBuilder\Core\Model\ResourceModel\File\Collection
     */
    public function getFileCollection($submissionId)
    {
        $collection = $this->fileCollectionFactory->create();
        $collection->addFieldToFilter('submission_id', $submissionId);
        return $collection;
    }

    /**
     * @return array
     */
    public function getFileValues()
    {
        $values = [];
        if ($submissionId = $this->getSubmissionId()) {
            $x     = 0;
            $files = $this->getFileCollection($submissionId);
            foreach ($files as $row) {
                $values[$x] = [
                    'name'    => $this->getFileFromPathFile($row->getFile()),
                    'html_id' => $row->getFileHash(),
                    'file'    => $row->getFile()
                ];
                if (file_exists($row->getFileAbsolutePath())) {
                    $values[$x]['size'] = $this->_downloadableFile->getFileSize(\BlueFormBuilder\Core\Model\File::UPLOAD_FOLDER . $row->getFile());
                }
                $x++;
            }
        }
 
        return $values;
    }

    /**
     * Return file name form file path
     *
     * @param string $pathFile
     * @return string
     */
    public function getFileFromPathFile($pathFile)
    {
        $file = substr($pathFile, strrpos($pathFile, '/') + 1);
        return $file;
    }

    /**
     * @return string
     */
    public function getFileUploadMeta()
    {
        $element     = $this->getElement();
        $maxFileSize = $element->getData('max_file_size');
        $minFileSize = $element->getData('min_file_size');
        $maxFiles    = $element->getData('max_files');
        $minFiles    = $element->getData('min_files');

        $fileMeta = [];
        if ($maxFileSize) {
            $fileMeta[] = __('Max file size: %1 KB', $maxFileSize);
        }
        if ($minFileSize) {
            $fileMeta[] = __('Min file size: %1 KB', $minFileSize);
        }
        if ($allowedExttensions = $element->getData('allowed_exttensions')) {
            $fileMeta[] = __('Allow file types: %1', $allowedExttensions);
        }
        if ($maxFiles) {
            $fileMeta[] = __('Max number of files: %1', $maxFiles);
        }
        if ($minFiles) {
            $fileMeta[] = __('Min number of files: %1', $minFiles);
        }

        $fileUploadMeta = '';
        foreach ($fileMeta as $i => $val) {
            $fileUploadMeta .= $val;
            if ($i < (count($fileMeta)-1)) {
                $fileUploadMeta .= ' | ';
            }
        }
        return $fileUploadMeta;
    }

    public function getAllowedExttensions() {
		$element = $this->getElement();
    	$allowedExttensions = $element->getData('allowed_exttensions');
		$allowedExttensions = explode(',', $allowedExttensions);
		foreach ($allowedExttensions as &$_extension) {
			$_extension = str_replace(' ', '', $_extension);
			$length = strlen($_extension);
			if ((substr($_extension, 0, $length) !== '.')) {
				$_extension = '.' . $_extension;
			}
		}
		$allowedExttensions = implode(',', $allowedExttensions);
		$allowedExttensions = 'accept="' . $allowedExttensions . '"';
		return $allowedExttensions;
    }

    public function getUploadUrl()
    {
    	return $this->getUrl('blueformbuilder/form/upload', ['id' => $this->getElemName() . 'insert']);
    }
}