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

    public function getName() : string {
        return $this->getLibName();
    }
}
