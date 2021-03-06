<?php

use FelixL7\Asset\CDNs\Cdnjs;
use FelixL7\Asset\CDNs\JsDelivr;

return [
    //main cdn -> required
    'cdn' => Cdnjs::class,

    //libraries
    'libs' => [
        //libName, must match $libName in library
        'bootstrap' => [
            //use asset version -> required
            'version' => '5.0.1',
            //overwrite main cdn
            'cdn' => JsDelivr::class,
        ],
        'jquery' => [
            'version' => '3.6.0',
        ],
        'swiper' => [
            'version' => '6.5.8',
        ],
    ]
];
