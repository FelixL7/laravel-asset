<?php

namespace FelixL7\Asset\Libs;

use FelixL7\Asset\AbstractLib;
use FelixL7\Asset\Interfaces\ICdnEntry;

class JQuery extends AbstractLib implements ICdnEntry
{
    protected $libName = 'jquery';
    protected $has = [
        'css' => false,
        'js' => true,
    ];
}
