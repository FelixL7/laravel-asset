<?php

namespace FelixL7\Cdn\Libs;

use FelixL7\Cdn\AbstractLib;
use FelixL7\Cdn\Interfaces\ICdnEntry;

class JQuery extends AbstractLib implements ICdnEntry
{
    protected $libName = 'jquery';
    protected $has = [
        'css' => false,
        'js' => true,
    ];

    public function getName() : string {
        return $this->getLibName();
    }
}
