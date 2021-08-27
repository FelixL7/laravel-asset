<?php

namespace FelixL7\Resource\Libs;

use FelixL7\Resource\AbstractLib;
use FelixL7\Resource\CDNs\Cdnjs;
use FelixL7\Resource\Interfaces\ICdnEntry;

class Swiper extends AbstractLib implements ICdnEntry
{
    protected $libName = 'swiper';
    protected $has = [
        'css' => true,
        'js' => true,
    ];

    protected function getLibNamePathSegment() : string {
        switch ($this->getCdn()) {
            case Cdnjs::class:
                return 'Swiper';
        }

        return $this->getLibName();
    }

    protected function getCssFileName() : string {
        return $this->cdnFileName();
    }

    protected function getJsFileName() : string {
        return $this->cdnFileName();
    }

    private function cdnFileName() : string {
        return 'swiper-bundle';
    }
}
