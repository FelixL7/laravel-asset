<?php

namespace FelixL7\Cdn\Libs;

use FelixL7\Cdn\AbstractLib;
use FelixL7\Cdn\CDNs\Cdnjs;
use FelixL7\Cdn\Interfaces\ICdnEntry;

class Swiper extends AbstractLib implements ICdnEntry
{
    protected $libName = 'swiper';
    protected $has = [
        'css' => true,
        'js' => true,
    ];

    public function getName() : string {
        return $this->getLibName();
    }

    /**
     *
     * @return string
     */
    protected function getLibNamePathSegment() : string {
        switch ($this->getCdn()) {
            case Cdnjs::class:
                return 'Swiper';
        }

        return $this->getLibName();
    }

    protected function getCssFileName() : string {
        return $this->cdnjsFileName();
    }

    protected function getJsFileName() : string {
        return $this->cdnjsFileName();
    }

    protected function cdnjsFileName() : string {
        return 'swiper-bundle';
    }
}
