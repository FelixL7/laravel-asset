<?php

namespace FelixL7\Resource\Facades;

use FelixL7\Resource\Resource as ResourceResource;
use Illuminate\Support\Facades\Facade;

class Resource extends Facade
{
    protected static function getFacadeAccessor() {
        return ResourceResource::class;
    }
}
