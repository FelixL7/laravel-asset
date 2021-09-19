<?php

namespace FelixL7\Asset\Libs;

use FelixL7\Asset\AbstractLib;
use FelixL7\Asset\Interfaces\ICdnEntry;

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
