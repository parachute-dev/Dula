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

interface ElementInterface
{
    public function beforeSave();

    public function afterSave();

    public function prepareValue($value);

    public function setPost($post);

    public function getPost();

    public function setValue($value);

    public function getValue();

    public function setHtmlValue($value);

    public function getHtmlValue();

    public function setEmailHtmlValue($value);

    public function getEmailHtmlValue();

    public function setForm(\BlueFormBuilder\Core\Model\Form $form);

    public function getForm();

    public function setSubmission(\BlueFormBuilder\Core\Model\Submission $submission);

    public function getSubmission();

    public function getBuilderElement();

    public function success();
}