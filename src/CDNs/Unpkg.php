<?php

namespace FelixL7\Cdn\CDNs;

use FelixL7\Cdn\Interfaces\ICdn;

class Unpkg extends AbstractCDN implements ICdn
{
    public const DOMAIN = 'https://unpkg.com';
    public const LIB_PATH = '/';

    public function getCSSFilePathWithoutExtension() : string {
        return $this->getFilePathWithoutExtension();
    }

    public function getJsFilePathWithoutExtension() : string {
        return $this->getFilePathWithoutExtension();
    }

    public function getFilePathWithoutExtension(array $args = []) : string {
        return self::DOMAIN.self::LIB_PATH.$this->libPathName.'@'.$this->version.'/'.$this->fileName;
    }
}