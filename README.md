# laravel-resource

## Installation

```bash
composer require felixl7/laravel-resource
```

## Register Libs

You can register libraries everywhere with the register method.

```php
use FelixL7\Resource\Facades\Resource;
use FelixL7\Resource\Libs\Bootstrap;
use FelixL7\Resource\Libs\JQuery;
use FelixL7\Resource\Libs\Swiper;

Resource::register([
    Bootstrap::class,
    JQuery::class,
    Swiper::class
]);
```

## Configuration

```php
use FelixL7\Resource\CDNs\Cdnjs;
use FelixL7\Resource\CDNs\JsDelivr;

return [
    //main cdn -> required
    'cdn' => Cdnjs::class,

    //libraries
    'libs' => [
        //libName, must match $libName in library
        'bootstrap' => [
            //use resource version -> required
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
use FelixL7\Resource\Facades\Resource;

Resource::bootstrap()->min()->async()->disableCacheWithConfigVersion()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js?v=1" async></script>

Resource::bootstrap()->min()->css();
//<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">
```

Or in Blade templates:
```php
@inject('resource', FelixL7\Resource\Resource::class)

{{$resource->bootstrap()->min()->async()->disableCacheWithConfigVersion()->js()}}
```

### Available Methods

```php
use FelixL7\Resource\Facades\Resource;

// Example
//Resource::bootstrap()->js()
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//min()
Resource::bootstrap()->min()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"></script>

//readable()
Resource::bootstrap()->readable()->js();
Resource::bootstrap()->min()->readable()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//version(string $version)
Resource::bootstrap()->version('4.0.0')->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.js"></script>

//configVersion()
//Version from config file
Resource::bootstrap()->configVersion()->js();
Resource::bootstrap()->version('4.0.0')->configVersion()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//disableCache()
Resource::bootstrap()->disableCache()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v=1630062180"></script>

//disableCacheWithConfigVersion()
Resource::bootstrap()->disableCacheWithConfigVersion()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v=1"></script>

//enableCache()
Resource::bootstrap()->enableCache()->js();
Resource::bootstrap()->disableCache()->enableCache()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//cdn(string $cdn)
Resource::bootstrap()->cdn(\FelixL7\Resource\CDNs\Cdnjs::class)->js();
//<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/dist/js/bootstrap.js"></script>

//configCdn()
Resource::bootstrap()->configCdn()->js();
Resource::bootstrap()->cdn(\FelixL7\Resource\CDNs\Cdnjs::class)->configCdn()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//hasCss() : bool
Resource::bootstrap()->hasCss();
//true

//hasJs() : bool
Resource::bootstrap()->hasJs();
//true
```

### Available CSS Methods

```php
//css()
Resource::bootstrap()->css();
//<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css" rel="stylesheet">
//If hasCss() returns false, en empty string will be returned

//cssUrl()
Resource::bootstrap()->cssUrl();
//https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css
//If hasCss() returns false, en empty string will be returned
```

### Available JS Methods

```php
//js()
Resource::bootstrap()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>
//If hasJs() returns false, en empty string will be returned

//jsUrl()
Resource::bootstrap()->jsUrl();
//https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js
//If hasJs() returns false, en empty string will be returned

//sync()
Resource::bootstrap()->sync()->js();
Resource::bootstrap()->defer()->sync()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>

//async()
Resource::bootstrap()->async()->js();
Resource::bootstrap()->defer()->async()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" async></script>

//defer()
Resource::bootstrap()->defer()->js();
Resource::bootstrap()->async()->defer()->js();
//<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" defer></script>
```
