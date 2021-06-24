<?php

namespace FelixL7\Cdn\CDNs;

use FelixL7\Cdn\Interfaces\ICdn;

class JsDelivr extends AbstractCDN implements ICdn
{
    public const DOMAIN = 'https://cdn.jsdelivr.net';
    public const LIB_PATH = '/npm';

    public function getDomain() : string {
        return self::DOMAIN;
    }

    protected function getLibPath() : string {
        return self::LIB_PATH;
    }
}
