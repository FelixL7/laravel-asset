<?php

namespace FelixL7\Resource\CDNs;

use FelixL7\Resource\Interfaces\ICdn;

class Unpkg extends AbstractCdn implements ICdn
{
    public const DOMAIN = 'https://unpkg.com';
    public const LIB_PATH = '';

    public function getDomain() : string {
        return self::DOMAIN;
    }

    protected function getLibPath() : string {
        return self::LIB_PATH;
    }

    protected function getFilePathPart($part, $key) {
        switch ($key) {
            case 0:
                return $part;
            case 3:
                return "@{$part}";

            default:
                return "/{$part}";
        }
    }
}
