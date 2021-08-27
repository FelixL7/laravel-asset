<?php

use FelixL7\Resource\CDNs\Cdnjs;
use FelixL7\Resource\CDNs\JsDelivr;

return [
    //main cdn
    'cdn' => Cdnjs::class,

    //libraries
    'libs' => [
        'bootstrap' => [
            //use resource version
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
