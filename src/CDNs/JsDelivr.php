<?php

namespace FelixL7\Cdn\CDNs;

use FelixL7\Cdn\Interfaces\ICdn;

class JsDelivr extends AbstractCDN implements ICdn
{
    public const DOMAIN = 'https://cdn.jsdelivr.net';
    public const LIB_PATH = '/npm/';

    public function getCSSFilePathWithoutExtension() : string {
        return self::DOMAIN.self::LIB_PATH.$this->libPathName.'@'.$this->version.'/dist/css/'.$this->fileName;
    }

    public function getJsFilePathWithoutExtension() : string {
        return self::DOMAIN.self::LIB_PATH.$this->libPathName.'@'.$this->version.'/dist/js/'.$this->fileName;
    }

    public function getFilePathWithoutExtension(array $args = []) : string {
        return self::DOMAIN.self::LIB_PATH.$this->libPathName.'@'.$this->version.'/dist/';
    }
}
