<?php

namespace FelixL7\Resource\Libs;

use FelixL7\Resource\AbstractLib;
use FelixL7\Resource\Interfaces\ICdnEntry;

class JQuery extends AbstractLib implements ICdnEntry
{
    protected $libName = 'jquery';
    protected $has = [
        'css' => false,
        'js' => true,
    ];
}
