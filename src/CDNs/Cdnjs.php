<?php

namespace FelixL7\Resource\CDNs;

use FelixL7\Resource\Interfaces\ICdn;

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
