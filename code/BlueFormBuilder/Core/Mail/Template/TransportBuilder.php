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

namespace BlueFormBuilder\Core\Mail\Template;

class TransportBuilder extends \BlueFormBuilder\Core\Framework\Mail\Template\TransportBuilder
{
    protected $_body;
    protected $_subject;
    protected $_parts = [];

    /**
     * @var \BlueFormBuilder\Core\Mail\Message
     */
    protected $message;

    public function setEmailBody($body)
    {
        $this->_body = $body;
        return $this;
    }

    public function getEmailBody()
    {
        return $this->_body;
    }

    public function setEmailSubject($subject)
    {
        $this->_subject = $subject;
        return $this;
    }

    public function getEmailSubject()
    {
        return $this->_subject;
    }

    public function getProductMetadata()
    {
        return $this->objectManager->get('Magento\Framework\App\ProductMetadataInterface');
    }

    /**
     * Prepare message
     *
     * @return $this
     */
    protected function prepareMessage()
    {
        if ($this->getProductMetadata()->getVersion() < '2.3.0') {
            $this->message->setMessageType('text/html')
            ->setBody($this->getEmailBody())
            ->setSubject(html_entity_decode($this->getEmailSubject(), ENT_QUOTES));
        } else {
            $this->message->setSubject(html_entity_decode($this->getEmailSubject(), ENT_QUOTES));
            $parts         = $this->getParts();
            $content       = new \Zend\Mime\Part($this->getEmailBody());
            $content->type = 'text/html';
            $parts[]       = $content;
            $mimeMessage   = new \Zend\Mime\Message();
            $mimeMessage->setParts($parts);
            $this->message->setBody($mimeMessage);
        }
        return $this;
    }

    public function addAttachment($fileName, $fileContent, $mineType)
    {
        if ($this->getProductMetadata()->getVersion() < '2.3.0') {
            $this->message->createAttachment(
                $fileContent,
                $mineType,
                \Zend_Mime::DISPOSITION_ATTACHMENT,
                \Zend_Mime::ENCODING_BASE64,
                $fileName
            );
        } else  {
            $this->createAttachment(
                $fileContent,
                $mineType,
                \Zend_Mime::DISPOSITION_ATTACHMENT,
                \Zend_Mime::ENCODING_BASE64,
                $fileName
            );
        }

        return $this;
    }

    public function createAttachment(
        $body,
        $mimeType    = \Zend_Mime::TYPE_OCTETSTREAM,
        $disposition = \Zend_Mime::DISPOSITION_ATTACHMENT,
        $encoding    = \Zend_Mime::ENCODING_BASE64,
        $filename    = null
    ) {
        $mp = new \Zend\Mime\Part($body);
        $mp->encoding    = $encoding;
        $mp->type        = $mimeType;
        $mp->disposition = $disposition;
        $mp->filename    = $filename;

        $this->_addAttachment($mp);

        return $mp;
    }

    /**
     * Adds an existing attachment to the mail message
     *
     * @param  Zend_Mime_Part $attachment
     * @return Zend_Mail Provides fluent interface
     */
    public function _addAttachment($attachment)
    {
        $this->addPart($attachment);
        return $this;
    }

    public function addPart($part)
    {
        $this->_parts[] = $part;
    }

    public function getParts()
    {
        return $this->_parts;
    }
}
