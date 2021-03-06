<?php


namespace FelixL7\Asset\Tests\Unit\Libs;

use FelixL7\Asset\CDNs\Cdnjs;
use FelixL7\Asset\CDNs\JsDelivr;
use FelixL7\Asset\CDNs\Unpkg;
use FelixL7\Asset\Libs\Bootstrap;
use FelixL7\Asset\Tests\BaseTest;

class BootstrapTest extends BaseTest
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('laravel-asset.libs.bootstrap',[
            'version' => '5.0.1',
            //Default CDN for Bootstrap is JsDelivr
            'cdn' => JsDelivr::class,
        ]);
    }

    protected function resetCdnConfig() {
        parent::resetCdnConfig();

        app()['config']->set('laravel-asset.libs.bootstrap',[
            'version' => '5.0.1',
            //Default CDN for Bootstrap is JsDelivr
            'cdn' => JsDelivr::class,
        ]);
    }

    public function testProperties() {
        $this->assertTrue((new Bootstrap)->hasCss());
        $this->assertTrue((new Bootstrap)->hasJs());
        $this->assertEquals('bootstrap', (new Bootstrap)->getName());
        $this->assertEquals('bootstrap', (new Bootstrap)->getLibName());
    }

    public function testScriptTag()
    {
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>', (new Bootstrap)->sync()->js());
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>', (new Bootstrap)->defer()->sync()->js());
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js"></script>', (new Bootstrap)->async()->sync()->js());
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" async></script>', (new Bootstrap)->async()->js());
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" async></script>', (new Bootstrap)->defer()->async()->js());
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" defer></script>', (new Bootstrap)->defer()->js());
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" defer></script>', (new Bootstrap)->async()->defer()->js());
    }

    public function testLinkTag()
    {
        $this->assertEquals('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css" rel="stylesheet">', (new Bootstrap)->css());
        $this->assertEquals('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet">', (new Bootstrap)->min()->css());
        $this->assertEquals('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css" rel="stylesheet">', (new Bootstrap)->defer()->css());
        $this->assertEquals('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css" rel="stylesheet">', (new Bootstrap)->async()->css());
    }

    public function testUrlBuilding() {
        //JS
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', (new Bootstrap)->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', (new Bootstrap)->min()->readable()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js', (new Bootstrap)->min()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js?v=1', (new Bootstrap)->min()->disableCacheWithConfigVersion()->jsUrl());

        //CSS
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', (new Bootstrap)->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', (new Bootstrap)->min()->readable()->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css', (new Bootstrap)->min()->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css?v=1', (new Bootstrap)->min()->disableCacheWithConfigVersion()->cssUrl());
    }

    public function testVersionSelection() {
        $version = '6.3.2';

        //JS
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', (new Bootstrap)->jsUrl());
        $this->assertEquals("https://cdn.jsdelivr.net/npm/bootstrap@{$version}/dist/js/bootstrap.js", (new Bootstrap)->version($version)->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', (new Bootstrap)->configVersion()->jsUrl());
        $this->assertEquals("https://cdn.jsdelivr.net/npm/bootstrap@{$version}/dist/js/bootstrap.js", (new Bootstrap)->configVersion()->version($version)->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', (new Bootstrap)->version($version)->configVersion()->jsUrl());

        //CSS
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', (new Bootstrap)->cssUrl());
        $this->assertEquals("https://cdn.jsdelivr.net/npm/bootstrap@{$version}/dist/css/bootstrap.css", (new Bootstrap)->version($version)->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', (new Bootstrap)->configVersion()->cssUrl());
        $this->assertEquals("https://cdn.jsdelivr.net/npm/bootstrap@{$version}/dist/css/bootstrap.css", (new Bootstrap)->configVersion()->version($version)->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', (new Bootstrap)->version($version)->configVersion()->cssUrl());
    }

    public function testCaching() {
        //JS
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v=1', (new Bootstrap)->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v='.time(), (new Bootstrap)->disableCache()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', (new Bootstrap)->enableCache()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', (new Bootstrap)->jsUrl());

        //CSS
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css?v=1', (new Bootstrap)->disableCacheWithConfigVersion()->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css?v='.time(), (new Bootstrap)->disableCache()->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', (new Bootstrap)->enableCache()->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', (new Bootstrap)->cssUrl());

        //Sequence
        $bootstrap = new Bootstrap;
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css?v='.time(), $bootstrap->disableCache()->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css?v=1', $bootstrap->disableCacheWithConfigVersion()->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', $bootstrap->enableCache()->cssUrl());

        //Cache from config version
        $customCacheVersion = '2.0.1';
        app()['config']->set('laravel-asset.cache_version', $customCacheVersion);
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v='.$customCacheVersion, (new Bootstrap)->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css?v='.$customCacheVersion, (new Bootstrap)->disableCacheWithConfigVersion()->cssUrl());

        $customLibCacheVersion = '3.5.2';
        app()['config']->set('laravel-asset.libs.bootstrap.cache_version', $customLibCacheVersion);
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v='.$customLibCacheVersion, (new Bootstrap)->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css?v='.$customLibCacheVersion, (new Bootstrap)->disableCacheWithConfigVersion()->cssUrl());

        $this->resetCdnConfig();
    }

    public function testCdnChange() {
        $bootstrap = new Bootstrap;
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', $bootstrap->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', $bootstrap->cssUrl());

        $bootstrap->cdn(Unpkg::class);
        $this->assertEquals('https://unpkg.com/bootstrap@5.0.1/dist/js/bootstrap.js', $bootstrap->jsUrl());
        $this->assertEquals('https://unpkg.com/bootstrap@5.0.1/dist/css/bootstrap.css', $bootstrap->cssUrl());

        $bootstrap->configCdn();
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', $bootstrap->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', $bootstrap->cssUrl());

        //Lib CDN changed in config
        app()['config']->set('laravel-asset.libs.bootstrap.cdn', Unpkg::class);
        $bootstrap->configCdn();
        $this->assertEquals('https://unpkg.com/bootstrap@5.0.1/dist/js/bootstrap.js', $bootstrap->jsUrl());
        $this->assertEquals('https://unpkg.com/bootstrap@5.0.1/dist/css/bootstrap.css', $bootstrap->cssUrl());

        //No lib CDN set -> default CDN
        app()['config']->set('laravel-asset.cdn', Cdnjs::class);
        app()['config']->set('laravel-asset.libs.bootstrap',[
            'version' => '5.0.1',
        ]);
        $bootstrap->configCdn();
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/dist/js/bootstrap.js', $bootstrap->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/dist/css/bootstrap.css', $bootstrap->cssUrl());
    }

    public function testConfigOptions() {
        //Lib Config Settings
        app()['config']->set('laravel-asset.libs.bootstrap.min', true);
        $this->assertTrue((new Bootstrap)->getMin());
        app()['config']->set('laravel-asset.libs.bootstrap.min', false);
        $this->assertFalse((new Bootstrap)->getMin());
       
        app()['config']->set('laravel-asset.libs.bootstrap.cache', false);
        $this->assertFalse((new Bootstrap)->getCache());
        app()['config']->set('laravel-asset.libs.bootstrap.cache', true);
        $this->assertTrue((new Bootstrap)->getCache());
        
        app()['config']->set('laravel-asset.libs.bootstrap.cache_config_version', true);
        $this->assertTrue((new Bootstrap)->getCacheConfigVersion());
        app()['config']->set('laravel-asset.libs.bootstrap.cache_config_version', false);
        $this->assertFalse((new Bootstrap)->getCacheConfigVersion());

        app()['config']->set('laravel-asset.libs.bootstrap.loading_attribute', 'async');
        $this->assertEquals('async', (new Bootstrap)->getLoadingAttribute());
        app()['config']->set('laravel-asset.libs.bootstrap.loading_attribute', 'defer');
        $this->assertEquals('defer', (new Bootstrap)->getLoadingAttribute());
        app()['config']->set('laravel-asset.libs.bootstrap.loading_attribute', null);
        $this->assertEquals(null, (new Bootstrap)->getLoadingAttribute());
        
        $this->resetCdnConfig();

        //Global Config Settings
        app()['config']->set('laravel-asset.min', true);
        $this->assertTrue((new Bootstrap)->getMin());
        app()['config']->set('laravel-asset.min', false);
        $this->assertFalse((new Bootstrap)->getMin());

        app()['config']->set('laravel-asset.cache', false);
        $this->assertFalse((new Bootstrap)->getCache());
        app()['config']->set('laravel-asset.cache', true);
        $this->assertTrue((new Bootstrap)->getCache());
        
        app()['config']->set('laravel-asset.cache_config_version', true);
        $this->assertTrue((new Bootstrap)->getCacheConfigVersion());
        app()['config']->set('laravel-asset.cache_config_version', false);
        $this->assertFalse((new Bootstrap)->getCacheConfigVersion());

        app()['config']->set('laravel-asset.loading_attribute', 'async');
        $this->assertEquals('async', (new Bootstrap)->getLoadingAttribute());
        app()['config']->set('laravel-asset.loading_attribute', 'defer');
        $this->assertEquals('defer', (new Bootstrap)->getLoadingAttribute());
        app()['config']->set('laravel-asset.loading_attribute', null);
        $this->assertEquals(null, (new Bootstrap)->getLoadingAttribute());
    }
}
