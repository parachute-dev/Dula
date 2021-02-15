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

namespace BlueFormBuilder\Core\Model\Element;

use BlueFormBuilder\Core\Model\File as FileModel;
use Magento\Framework\App\Filesystem\DirectoryList;

class File extends \BlueFormBuilder\Core\Model\Element
{
    /**
     * @var array
     */
	protected $files = [];

    /**
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    protected $coreFileStorageDatabase;

    /**
     * @var \Magento\Downloadable\Helper\File
     */
    protected $_downloadableFile;

    /**
     * @var \Magento\Framework\File\Mime
     */
    protected $mime;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $mediaDirectory;

    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \BlueFormBuilder\Core\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory
     */
    protected $fileCollectionFactory;

    /**
     * @param \Magezon\Builder\Data\Elements                                   $builderElements         
     * @param \Magento\MediaStorage\Helper\File\Storage\Database               $coreFileStorageDatabase 
     * @param \Magento\Downloadable\Helper\File                                $downloadableFile        
     * @param \Magento\Framework\File\Mime                                     $mime                    
     * @param \Magento\Framework\App\ResourceConnection                        $resource                
     * @param \Magento\Framework\Filesystem                                    $filesystem              
     * @param \Magento\Framework\UrlInterface                                  $urlBuilder              
     * @param \BlueFormBuilder\Core\Helper\Data                                $dataHelper              
     * @param \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory   
     * @param array                                                            $data                    
     */
    public function __construct(
        \Magezon\Builder\Data\Elements $builderElements,
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Downloadable\Helper\File $downloadableFile,
        \Magento\Framework\File\Mime $mime,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\UrlInterface $urlBuilder,
        \BlueFormBuilder\Core\Helper\Data $dataHelper,
        \BlueFormBuilder\Core\Model\ResourceModel\File\CollectionFactory $fileCollectionFactory,
        array $data = []
    ) {
        parent::__construct($builderElements, $data);
        $this->coreFileStorageDatabase = $coreFileStorageDatabase;
        $this->_downloadableFile       = $downloadableFile;
        $this->mime                    = $mime;
        $this->_resource               = $resource;
        $this->mediaDirectory          = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->urlBuilder              = $urlBuilder;
        $this->dataHelper              = $dataHelper;
        $this->fileCollectionFactory   = $fileCollectionFactory;
    }

    public function getFileHash($file)
    {
        $uniqueFileName = $this->getUniqueFilename(FileModel::UPLOAD_FOLDER, $file);
        $_fileHash = base64_encode($uniqueFileName) . 'BFB' . base64_encode(date('Y-m-d'));
        $_fileHash = str_replace('==', '', $_fileHash);
        $_fileHash = substr($_fileHash, 4);
        return $_fileHash;
    }

	public function prepareValue($value)
	{
        $form      = $this->getForm();
        $directory = $this->mediaDirectory->getAbsolutePath(FileModel::UPLOAD_TMP) . '/' . $form->getBfbFormKey();
        $files     = explode(',', $value);

        if (count($files)) {
            $names    = [];
            $value    = '<div class="bfb-file-list">';
            $newFiles = [];
            foreach ($files as $file) {
                if (!$file) continue;
                $newFiles[] = $file;
            }
    		foreach ($newFiles as $index => $file) {
                $_file          = $file;
                $_path          = $directory . $file;
                $_fileSizeLabel = '';
                $_fileSize      = 0;
                if (file_exists($_path)) {
    				$_fileSize      = $this->_downloadableFile->getFileSize($_path);
    				$_fileSizeLabel = $this->dataHelper->byteconvert($_fileSize);
                }
                $names[] = $_fileName = $this->getFileFromPathFile($this->getUniqueFilename(FileModel::UPLOAD_FOLDER, $file));
                $_fileHash = $this->getFileHash($_fileName);

                $this->files[] = [
    				'file'       => $_file,
    				'element_id' => $this->getElemId(),
    				'file_hash'  => $_fileHash,
    				'path'       => $_path,
    				'mine_type'  => file_exists($_path) ? $this->getMimeType($_path) : '',
    				'name'       => $_fileName,
    				'size'       => $_fileSize
                ];

                $_fileUrl = $this->urlBuilder->getUrl('blueformbuilder/file/download', [
                    'id'  => $this->getElemId(),
                    'key' => $_fileHash
                ]);

                $value .= '<div class="bfb-file-row">';
                if (!$this->getSkipSaveSubmission()) $value .= '<a class="bfb-file-info" href="' . $_fileUrl . '" target="_blank">';
                $value .=  $_fileName;
                if ($_fileSizeLabel) $value .= ' (' . $_fileSizeLabel . ') ';
                if (!$this->getSkipSaveSubmission()) $value .= '</a>';
                $value .= '</div>';    
    		}
            $value .= '</div>';
            $this->setAttachments($this->files);
            $this->setValue(implode(', ', $names));
            $this->setHtmlValue($value);
            $this->setEmailHtmlValue($value);
        }
	}

    public function success()
    {
        $newFiles = [];
        $directory = $this->mediaDirectory->getAbsolutePath(FileModel::UPLOAD_FOLDER);
        foreach ($this->files as &$file) {
            $_file          = $file['file'];
            $_file          = $this->moveFileFromTmp($_file);
            $_path          = $directory . $_file;
            $_fileName      = $this->getFileFromPathFile($_file);
            $_fileHash      = $this->getFileHash($_fileName);
            $_fileSizeLabel = '';
            $_fileSize      = 0;
            if (file_exists($_path)) {
                $_fileSize      = $this->_downloadableFile->getFileSize($_path);
                $_fileSizeLabel = $this->dataHelper->byteconvert($_fileSize);
            }
            $newFiles[] = [
                'file'       => $_file,
                'element_id' => $this->getElemId(),
                'file_hash'  => $_fileHash,
                'path'       => $_path,
                'mine_type'  => file_exists($_path) ? $this->getMimeType($_path) : '',
                'name'       => $_fileName,
                'size'       => $_fileSize
            ];
        }
        $this->saveFiles($newFiles);
    }

    /**
     * Checking file for moving and move it
     *
     * @param string $fileName
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function moveFileFromTmp($fileName)
    {
        $formKey          = $this->getForm()->getBfbFormKey();
        $uniqueFileName   = $this->getUniqueFilename(FileModel::UPLOAD_FOLDER, $fileName);
        $baseImagePath    = $this->getFilePath(FileModel::UPLOAD_FOLDER, $uniqueFileName);
        $baseTmpImagePath = $this->getFilePath(FileModel::UPLOAD_TMP, '/' . $formKey . $fileName);

        if (file_exists($this->mediaDirectory->getAbsolutePath($baseTmpImagePath))) {
            $baseImagePath = str_replace('/' . $formKey, '', $baseImagePath);
            try {
                $this->coreFileStorageDatabase->copyFile(
                    $baseTmpImagePath,
                    $baseImagePath
                );
                $this->mediaDirectory->renameFile(
                    $baseTmpImagePath,
                    $baseImagePath
                );
            } catch (\Exception $e) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while saving the file(s).')
                );
            }
        } else {
            $uniqueFileName = $fileName;
        }

        return $uniqueFileName;
    }

    /**
     * Return file name form file path
     *
     * @param string $pathFile
     * @return string
     */
    public function getFileFromPathFile($pathFile)
    {
        return substr($pathFile, strrpos($pathFile, '/') + 1);
    }

    /**
     * Retrieve path
     *
     * @param string $path
     * @param string $imageName
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }

    /**
     * Get unique name for passed file in case this file already exists
     *
     * @param string $directory
     * @param string $filename
     * @return string
     */
    public function getUniqueFilename($directory, $filename)
    {
        $directory = $this->mediaDirectory->getAbsolutePath($directory);
        if (file_exists($directory . $filename)) {
            $index = 1;
            $extension = strrchr($filename, '.');
            $filenameWoExtension = substr($filename, 0, -1 * strlen($extension));
            while (file_exists($directory . $filenameWoExtension . '_' . $index . $extension)) {
                $index++;
            }
            $filename = $filenameWoExtension . '_' . $index . $extension;
        }
        return $filename;
    }

    /**
     * Retrieve MIME type of requested file
     *
     * @param string $filename
     * @return string
     */
    public function getMimeType($absoluteFilePath)
    {
        return $this->mime->getMimeType($absoluteFilePath);
    }

    public function saveFiles($files)
    {
		$connection = $this->_resource->getConnection();
		$submission = $this->getSubmission();
		$table      = $this->_resource->getTableName('mgz_blueformbuilder_submission_file');
		$collection = $this->fileCollectionFactory->create();
		$collection->addFieldToFilter('submission_id', $submission->getId());
		$deleIds    = [];
		foreach ($collection as $file) {
            $delete = true;
            foreach ($files as $k => $_row) {
                if ($_row['file'] == $file->getFile()) {
                    $delete = false;
                    unset($files[$k]);
                }
            }
            if ($delete) $deleIds[] = $file->getId();
        }
		if ($deleIds) {
	        $where = ['file_id IN (?)' => $deleIds, 'element_id = ?' => $this->getElemId()];
	        $connection->delete($table, $where);
	    }

        if ($files) {
			$data = [];
            foreach ($files as $_row) {
                $data[] = [
					'submission_id' => $submission->getId(),
					'file_hash'     => $_row['file_hash'],
					'element_id'    => $this->getElemId(),
					'file'          => $_row['file'],
					'mine_type'     => $_row['mine_type'],
					'size'          => $_row['size'],
					'form_id'       => $this->getSubmission()->getFormId()
                ];
            }
            $connection->insertMultiple($table, $data);
        }
    }
}