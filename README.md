# laravel-asset

You can use this package to get any Frontend Asset from any CDN you want in a laravel-like notation.
```php
use FelixL7\Asset\Facades\Asset;

Asset::bootstrap()->min()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>
```

With this package come 3 CDN implementations:
* [cdnjs](https://cdnjs.com/)
* [jsDelivr](https://www.jsdelivr.com/)
* [UNPKG](https://unpkg.com/)

And 3 Libraries:
* [Bootstrap](https://getbootstrap.com/)
* [jQuery](https://jquery.com/)
* [Swiper](https://swiperjs.com/)

You can use these implementations to implement other CDNs and libraries, you want to use.<br>
It's really simple to add new. Try it out!

## Installation

```bash
composer require felixl7/laravel-asset
```

## Register Libs

You can register libraries everywhere with the register method.

```php
use FelixL7\Asset\Facades\Asset;
use FelixL7\Asset\Libs\Bootstrap;
use FelixL7\Asset\Libs\JQuery;
use FelixL7\Asset\Libs\Swiper;

Asset::register([
    Bootstrap::class,
    JQuery::class,
    Swiper::class
]);
```

## Configuration

```php
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
```

## Usage

If you registered the library you can use it like:
```php
use FelixL7\Asset\Facades\Asset;

Asset::bootstrap()->min()->async()->disableCacheWithConfigVersion()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js?v=1" async></script>

Asset::bootstrap()->min()->css();
//<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
```

Or in Blade templates:
```php
@inject('asset', FelixL7\Asset\Asset::class)

{{$asset->bootstrap()->min()->async()->disableCacheWithConfigVersion()->js()}}
```

### Available Methods

```php
use FelixL7\Asset\Facades\Asset;

// Example
//Asset::bootstrap()->js()
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//min()
Asset::bootstrap()->min()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>

//readable()
Asset::bootstrap()->readable()->js();
Asset::bootstrap()->min()->readable()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//version(string $version)
Asset::bootstrap()->version('4.0.0')->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.js"></script>

//configVersion()
//Version from config file
Asset::bootstrap()->configVersion()->js();
Asset::bootstrap()->version('4.0.0')->configVersion()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//disableCache()
Asset::bootstrap()->disableCache()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v=1630062180"></script>

//disableCacheWithConfigVersion()
Asset::bootstrap()->disableCacheWithConfigVersion()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v=1"></script>

//enableCache()
Asset::bootstrap()->enableCache()->js();
Asset::bootstrap()->disableCache()->enableCache()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//cdn(string $cdn)
Asset::bootstrap()->cdn(\FelixL7\Asset\CDNs\Cdnjs::class)->js();
//<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/dist/js/bootstrap.js"></script>

//configCdn()
Asset::bootstrap()->configCdn()->js();
Asset::bootstrap()->cdn(\FelixL7\Asset\CDNs\Cdnjs::class)->configCdn()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//hasCss() : bool
Asset::bootstrap()->hasCss();
//true

//hasJs() : bool
Asset::bootstrap()->hasJs();
//true
```

### Available CSS Methods

```php
//css()
Asset::bootstrap()->css();
//<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css" rel="stylesheet">
//If hasCss() returns false, en empty string will be returned

//cssUrl()
Asset::bootstrap()->cssUrl();
//https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css
//If hasCss() returns false, en empty string will be returned
```

### Available JS Methods

```php
//js()
Asset::bootstrap()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>
//If hasJs() returns false, en empty string will be returned

//jsUrl()
Asset::bootstrap()->jsUrl();
//https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js
//If hasJs() returns false, en empty string will be returned

//sync()
Asset::bootstrap()->sync()->js();
Asset::bootstrap()->defer()->sync()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//async()
Asset::bootstrap()->async()->js();
Asset::bootstrap()->defer()->async()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" async></script>

//defer()
Asset::bootstrap()->defer()->js();
Asset::bootstrap()->async()->defer()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" defer></script>
```
