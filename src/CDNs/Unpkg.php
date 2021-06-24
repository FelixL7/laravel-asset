<?php

namespace FelixL7\Cdn\CDNs;

use FelixL7\Cdn\Interfaces\ICdn;

class Unpkg extends AbstractCDN implements ICdn
{
    public const DOMAIN = 'https://unpkg.com';
    public const LIB_PATH = '';

    public function getDomain() : string {
        return self::DOMAIN;
    }

    protected function getLibPath() : string {
        return self::LIB_PATH;
    }
}
