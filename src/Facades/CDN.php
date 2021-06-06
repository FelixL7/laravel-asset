<?php

namespace FelixL7\Cdn\Facades;

use FelixL7\Cdn\CDN as CdnCDN;
use Illuminate\Support\Facades\Facade;

class CDN extends Facade
{
    protected static function getFacadeAccessor() {
        return CdnCDN::class;
    }
}
