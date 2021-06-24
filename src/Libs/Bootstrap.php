<?php

namespace FelixL7\Cdn\Libs;

use FelixL7\Cdn\AbstractLib;
use FelixL7\Cdn\Interfaces\ICdnEntry;

class Bootstrap extends AbstractLib implements ICdnEntry
{
    protected $libName = 'bootstrap';
    protected $has = [
        'css' => true,
        'js' => true,
    ];

    protected function cdnCssFilePathAfterVersion() : string {
        return "/dist/css";
    }

    protected function cdnJsFilePathAfterVersion() : string {
        return "/dist/js";
    }
}
