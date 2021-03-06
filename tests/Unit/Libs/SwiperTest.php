<?php


namespace FelixL7\Asset\Tests\Unit\Libs;

use FelixL7\Asset\CDNs\Cdnjs;
use FelixL7\Asset\CDNs\Unpkg;
use FelixL7\Asset\Libs\Swiper;
use FelixL7\Asset\Tests\BaseTest;

class SwiperTest extends BaseTest
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('laravel-asset.libs.swiper',[
            'version' => '7.0.1',
            //Default CDN for Swiper is Cdnjs
            'cdn' => Cdnjs::class,
        ]);
    }

    protected function resetCdnConfig() {
        parent::resetCdnConfig();

        app()['config']->set('laravel-asset.libs.swiper',[
            'version' => '7.0.1',
            //Default CDN for Swiper is Cdnjs
            'cdn' => Cdnjs::class,
        ]);
    }

    public function testProperties() {
        $this->assertTrue((new Swiper)->hasCss());
        $this->assertTrue((new Swiper)->hasJs());
        $this->assertEquals('swiper', (new Swiper)->getName());
        $this->assertEquals('swiper', (new Swiper)->getLibName());
    }

    public function testScriptTag()
    {
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js"></script>', (new Swiper)->sync()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js"></script>', (new Swiper)->defer()->sync()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js"></script>', (new Swiper)->async()->sync()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js" async></script>', (new Swiper)->async()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js" async></script>', (new Swiper)->defer()->async()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js" defer></script>', (new Swiper)->defer()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js" defer></script>', (new Swiper)->async()->defer()->js());
    }

    public function testLinkTag()
    {
        $this->assertEquals('<link href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css" rel="stylesheet">', (new Swiper)->css());
        $this->assertEquals('<link href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.min.css" rel="stylesheet">', (new Swiper)->min()->css());
        $this->assertEquals('<link href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css" rel="stylesheet">', (new Swiper)->defer()->css());
        $this->assertEquals('<link href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css" rel="stylesheet">', (new Swiper)->async()->css());
    }

    public function testUrlBuilding() {
        //JS
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', (new Swiper)->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', (new Swiper)->min()->readable()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.min.js', (new Swiper)->min()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.min.js?v=1', (new Swiper)->min()->disableCacheWithConfigVersion()->jsUrl());

        //CSS
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', (new Swiper)->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', (new Swiper)->min()->readable()->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.min.css', (new Swiper)->min()->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.min.css?v=1', (new Swiper)->min()->disableCacheWithConfigVersion()->cssUrl());
    }

    public function testVersionSelection() {
        $version = '6.3.2';

        //JS
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', (new Swiper)->jsUrl());
        $this->assertEquals("https://cdnjs.cloudflare.com/ajax/libs/Swiper/{$version}/swiper-bundle.js", (new Swiper)->version($version)->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', (new Swiper)->configVersion()->jsUrl());
        $this->assertEquals("https://cdnjs.cloudflare.com/ajax/libs/Swiper/{$version}/swiper-bundle.js", (new Swiper)->configVersion()->version($version)->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', (new Swiper)->version($version)->configVersion()->jsUrl());

        //CSS
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', (new Swiper)->cssUrl());
        $this->assertEquals("https://cdnjs.cloudflare.com/ajax/libs/Swiper/{$version}/swiper-bundle.css", (new Swiper)->version($version)->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', (new Swiper)->configVersion()->cssUrl());
        $this->assertEquals("https://cdnjs.cloudflare.com/ajax/libs/Swiper/{$version}/swiper-bundle.css", (new Swiper)->configVersion()->version($version)->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', (new Swiper)->version($version)->configVersion()->cssUrl());
    }

    public function testCaching() {
        //JS
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js?v=1', (new Swiper)->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js?v='.time(), (new Swiper)->disableCache()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', (new Swiper)->enableCache()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', (new Swiper)->jsUrl());

        //CSS
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css?v=1', (new Swiper)->disableCacheWithConfigVersion()->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css?v='.time(), (new Swiper)->disableCache()->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', (new Swiper)->enableCache()->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', (new Swiper)->cssUrl());

        //Sequence
        $swiper = new Swiper;
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css?v='.time(), $swiper->disableCache()->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css?v=1', $swiper->disableCacheWithConfigVersion()->cssUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', $swiper->enableCache()->cssUrl());

        //Cache from config version
        $customCacheVersion = '2.0.1';
        app()['config']->set('laravel-asset.cache_version', $customCacheVersion);
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js?v='.$customCacheVersion, (new Swiper)->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css?v='.$customCacheVersion, (new Swiper)->disableCacheWithConfigVersion()->cssUrl());

        $customLibCacheVersion = '3.5.2';
        app()['config']->set('laravel-asset.libs.swiper.cache_version', $customLibCacheVersion);
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js?v='.$customLibCacheVersion, (new Swiper)->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css?v='.$customLibCacheVersion, (new Swiper)->disableCacheWithConfigVersion()->cssUrl());

        $this->resetCdnConfig();
    }

    public function testCdnChange() {
        $swiper = new Swiper;
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', $swiper->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', $swiper->cssUrl());

        $swiper->cdn(Unpkg::class);
        $this->assertEquals('https://unpkg.com/swiper@7.0.1/swiper-bundle.js', $swiper->jsUrl());
        $this->assertEquals('https://unpkg.com/swiper@7.0.1/swiper-bundle.css', $swiper->cssUrl());

        $swiper->configCdn();
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', $swiper->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', $swiper->cssUrl());

        //Lib CDN changed in config
        app()['config']->set('laravel-asset.libs.swiper.cdn', Unpkg::class);
        $swiper->configCdn();
        $this->assertEquals('https://unpkg.com/swiper@7.0.1/swiper-bundle.js', $swiper->jsUrl());
        $this->assertEquals('https://unpkg.com/swiper@7.0.1/swiper-bundle.css', $swiper->cssUrl());

        //No lib CDN set -> default CDN
        app()['config']->set('laravel-asset.cdn', Cdnjs::class);
        app()['config']->set('laravel-asset.libs.swiper',[
            'version' => '7.0.1',
        ]);
        $swiper->configCdn();
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.js', $swiper->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.0.1/swiper-bundle.css', $swiper->cssUrl());
    }

    public function testConfigOptions() {
        //Lib Config Settings
        app()['config']->set('laravel-asset.libs.swiper.min', true);
        $this->assertTrue((new Swiper)->getMin());
        app()['config']->set('laravel-asset.libs.swiper.min', false);
        $this->assertFalse((new Swiper)->getMin());
       
        app()['config']->set('laravel-asset.libs.swiper.cache', false);
        $this->assertFalse((new Swiper)->getCache());
        app()['config']->set('laravel-asset.libs.swiper.cache', true);
        $this->assertTrue((new Swiper)->getCache());
        
        app()['config']->set('laravel-asset.libs.swiper.cache_config_version', true);
        $this->assertTrue((new Swiper)->getCacheConfigVersion());
        app()['config']->set('laravel-asset.libs.swiper.cache_config_version', false);
        $this->assertFalse((new Swiper)->getCacheConfigVersion());

        app()['config']->set('laravel-asset.libs.swiper.loading_attribute', 'async');
        $this->assertEquals('async', (new Swiper)->getLoadingAttribute());
        app()['config']->set('laravel-asset.libs.swiper.loading_attribute', 'defer');
        $this->assertEquals('defer', (new Swiper)->getLoadingAttribute());
        app()['config']->set('laravel-asset.libs.swiper.loading_attribute', null);
        $this->assertEquals(null, (new Swiper)->getLoadingAttribute());
        
        $this->resetCdnConfig();

        //Global Config Settings
        app()['config']->set('laravel-asset.min', true);
        $this->assertTrue((new Swiper)->getMin());
        app()['config']->set('laravel-asset.min', false);
        $this->assertFalse((new Swiper)->getMin());

        app()['config']->set('laravel-asset.cache', false);
        $this->assertFalse((new Swiper)->getCache());
        app()['config']->set('laravel-asset.cache', true);
        $this->assertTrue((new Swiper)->getCache());
        
        app()['config']->set('laravel-asset.cache_config_version', true);
        $this->assertTrue((new Swiper)->getCacheConfigVersion());
        app()['config']->set('laravel-asset.cache_config_version', false);
        $this->assertFalse((new Swiper)->getCacheConfigVersion());

        app()['config']->set('laravel-asset.loading_attribute', 'async');
        $this->assertEquals('async', (new Swiper)->getLoadingAttribute());
        app()['config']->set('laravel-asset.loading_attribute', 'defer');
        $this->assertEquals('defer', (new Swiper)->getLoadingAttribute());
        app()['config']->set('laravel-asset.loading_attribute', null);
        $this->assertEquals(null, (new Swiper)->getLoadingAttribute());
    }
}
