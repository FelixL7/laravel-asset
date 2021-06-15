<?php


namespace FelixL7\Cdn\Tests\Unit\Libs;

use FelixL7\Cdn\CDNs\JsDelivr;
use FelixL7\Cdn\Libs\Bootstrap;
use FelixL7\Cdn\Tests\BaseTest;

class BootstrapTest extends BaseTest
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('laravel-cdn.libs.bootstrap',[
            'version' => '5.0.1',
            //Default CDN for Bootstrap is JsDelivr
            'cdn' => JsDelivr::class,
        ]);
    }

    protected function resetCdnConfig() {
        parent::resetCdnConfig();

        app()['config']->set('laravel-cdn.libs.bootstrap',[
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
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" async></script>', (new Bootstrap)->async()->js());
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js" defer></script>', (new Bootstrap)->defer()->js());
    }

    public function testUrlBuilding() {
        //JS
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js', (new Bootstrap)->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js', (new Bootstrap)->min()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js?v=1', (new Bootstrap)->min()->disableCacheWithConfigVersion()->jsUrl());

        //CSS
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css', (new Bootstrap)->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css', (new Bootstrap)->min()->cssUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css?v=1', (new Bootstrap)->min()->disableCacheWithConfigVersion()->cssUrl());
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
        app()['config']->set('laravel-cdn.cache_version', $customCacheVersion);
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v='.$customCacheVersion, (new Bootstrap)->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css?v='.$customCacheVersion, (new Bootstrap)->disableCacheWithConfigVersion()->cssUrl());

        $customLibCacheVersion = '3.5.2';
        app()['config']->set('laravel-cdn.libs.bootstrap.cache_version', $customLibCacheVersion);
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.js?v='.$customLibCacheVersion, (new Bootstrap)->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.css?v='.$customLibCacheVersion, (new Bootstrap)->disableCacheWithConfigVersion()->cssUrl());

        $this->resetCdnConfig();
    }
}
