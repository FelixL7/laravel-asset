<?php

namespace FelixL7\Asset\Facades;

use FelixL7\Asset\Asset as AssetAsset;
use Illuminate\Support\Facades\Facade;

class Asset extends Facade
{
    protected static function getFacadeAccessor() {
        return AssetAsset::class;
    }
}
