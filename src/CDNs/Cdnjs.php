<?php

namespace FelixL7\Cdn\CDNs;

use FelixL7\Cdn\Interfaces\ICdn;

class Cdnjs extends AbstractCDN implements ICdn
{
    public const DOMAIN = 'https://cdnjs.cloudflare.com';
    public const LIB_PATH = '/ajax/libs';

    public function getDomain() : string {
        return self::DOMAIN;
    }

    protected function getLibPath() : string {
        return self::LIB_PATH;
    }
}
