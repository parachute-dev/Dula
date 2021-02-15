<?php
namespace Vtn\RemoveMetaTag\Plugin\PageConfig;

class RemoveMetaTag
{
    public function afterGetKeywords($subject, string $return)
    {
        return '';
    }
}