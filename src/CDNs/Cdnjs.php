<?php

namespace FelixL7\Asset\CDNs;

use FelixL7\Asset\Interfaces\ICdn;

class Cdnjs extends AbstractCdn implements ICdn
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
