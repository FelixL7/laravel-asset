<?php


namespace FelixL7\Asset\Tests\Unit\Libs;

use FelixL7\Asset\CDNs\Cdnjs;
use FelixL7\Asset\CDNs\Unpkg;
use FelixL7\Asset\Libs\JQuery;
use FelixL7\Asset\Tests\BaseTest;

class JQueryTest extends BaseTest
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('laravel-asset.libs.jquery',[
            'version' => '3.6.0',
            //Default CDN for jQuery is Cdnjs
            'cdn' => Cdnjs::class,
        ]);
    }

    protected function resetCdnConfig() {
        parent::resetCdnConfig();

        app()['config']->set('laravel-asset.libs.jquery',[
            'version' => '3.6.0',
            //Default CDN for jQuery is Cdnjs
            'cdn' => Cdnjs::class,
        ]);
    }

    public function testProperties() {
        $this->assertFalse((new JQuery)->hasCss());
        $this->assertTrue((new JQuery)->hasJs());
        $this->assertEquals('jquery', (new JQuery)->getName());
        $this->assertEquals('jquery', (new JQuery)->getLibName());
    }

    public function testScriptTag()
    {
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>', (new JQuery)->sync()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>', (new JQuery)->defer()->sync()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js"></script>', (new JQuery)->async()->sync()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" async></script>', (new JQuery)->async()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" async></script>', (new JQuery)->defer()->async()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" defer></script>', (new JQuery)->defer()->js());
        $this->assertEquals('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" defer></script>', (new JQuery)->async()->defer()->js());
    }

    public function testLinkTag()
    {
        $this->assertEquals('', (new JQuery)->css());
        $this->assertEquals('', (new JQuery)->defer()->css());
    }

    public function testUrlBuilding() {
        //JS
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', (new JQuery)->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', (new JQuery)->min()->readable()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', (new JQuery)->min()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js?v=1', (new JQuery)->min()->disableCacheWithConfigVersion()->jsUrl());

        //CSS
        $this->assertEquals('', (new JQuery)->cssUrl());
    }

    public function testVersionSelection() {
        $version = '3.3.1';

        //JS
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', (new JQuery)->jsUrl());
        $this->assertEquals("https://cdnjs.cloudflare.com/ajax/libs/jquery/{$version}/jquery.js", (new JQuery)->version($version)->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', (new JQuery)->configVersion()->jsUrl());
        $this->assertEquals("https://cdnjs.cloudflare.com/ajax/libs/jquery/{$version}/jquery.js", (new JQuery)->configVersion()->version($version)->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', (new JQuery)->version($version)->configVersion()->jsUrl());
    }

    public function testCaching() {
        //JS
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js?v=1', (new JQuery)->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js?v='.time(), (new JQuery)->disableCache()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', (new JQuery)->enableCache()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', (new JQuery)->jsUrl());

        //Sequence
        $jQuery = new JQuery;
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js?v='.time(), $jQuery->disableCache()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js?v=1', $jQuery->disableCacheWithConfigVersion()->jsUrl());
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', $jQuery->enableCache()->jsUrl());

        //Cache from config version
        $customCacheVersion = '2.0.1';
        app()['config']->set('laravel-asset.cache_version', $customCacheVersion);
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js?v='.$customCacheVersion, (new JQuery)->disableCacheWithConfigVersion()->jsUrl());

        $customLibCacheVersion = '3.5.2';
        app()['config']->set('laravel-asset.libs.jquery.cache_version', $customLibCacheVersion);
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js?v='.$customLibCacheVersion, (new JQuery)->disableCacheWithConfigVersion()->jsUrl());

        $this->resetCdnConfig();
    }

    public function testCdnChange() {
        $jQuery = new JQuery;
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', $jQuery->jsUrl());

        $jQuery->cdn(Unpkg::class);
        $this->assertEquals('https://unpkg.com/jquery@3.6.0/jquery.js', $jQuery->jsUrl());

        $jQuery->configCdn();
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', $jQuery->jsUrl());

        //Lib CDN changed in config
        app()['config']->set('laravel-asset.libs.jquery.cdn', Unpkg::class);
        $jQuery->configCdn();
        $this->assertEquals('https://unpkg.com/jquery@3.6.0/jquery.js', $jQuery->jsUrl());

        //No lib CDN set -> default CDN
        app()['config']->set('laravel-asset.cdn', Cdnjs::class);
        app()['config']->set('laravel-asset.libs.jquery',[
            'version' => '3.6.0',
        ]);
        $jQuery->configCdn();
        $this->assertEquals('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js', $jQuery->jsUrl());
    }

    public function testConfigOptions() {
        //Lib Config Settings
        app()['config']->set('laravel-asset.libs.jquery.min', true);
        $this->assertTrue((new JQuery)->getMin());
        app()['config']->set('laravel-asset.libs.jquery.min', false);
        $this->assertFalse((new JQuery)->getMin());
       
        app()['config']->set('laravel-asset.libs.jquery.cache', false);
        $this->assertFalse((new JQuery)->getCache());
        app()['config']->set('laravel-asset.libs.jquery.cache', true);
        $this->assertTrue((new JQuery)->getCache());
        
        app()['config']->set('laravel-asset.libs.jquery.cache_config_version', true);
        $this->assertTrue((new JQuery)->getCacheConfigVersion());
        app()['config']->set('laravel-asset.libs.jquery.cache_config_version', false);
        $this->assertFalse((new JQuery)->getCacheConfigVersion());

        app()['config']->set('laravel-asset.libs.jquery.loading_attribute', 'async');
        $this->assertEquals('async', (new JQuery)->getLoadingAttribute());
        app()['config']->set('laravel-asset.libs.jquery.loading_attribute', 'defer');
        $this->assertEquals('defer', (new JQuery)->getLoadingAttribute());
        app()['config']->set('laravel-asset.libs.jquery.loading_attribute', null);
        $this->assertEquals(null, (new JQuery)->getLoadingAttribute());
        
        $this->resetCdnConfig();

        //Global Config Settings
        app()['config']->set('laravel-asset.min', true);
        $this->assertTrue((new JQuery)->getMin());
        app()['config']->set('laravel-asset.min', false);
        $this->assertFalse((new JQuery)->getMin());

        app()['config']->set('laravel-asset.cache', false);
        $this->assertFalse((new JQuery)->getCache());
        app()['config']->set('laravel-asset.cache', true);
        $this->assertTrue((new JQuery)->getCache());
        
        app()['config']->set('laravel-asset.cache_config_version', true);
        $this->assertTrue((new JQuery)->getCacheConfigVersion());
        app()['config']->set('laravel-asset.cache_config_version', false);
        $this->assertFalse((new JQuery)->getCacheConfigVersion());

        app()['config']->set('laravel-asset.loading_attribute', 'async');
        $this->assertEquals('async', (new JQuery)->getLoadingAttribute());
        app()['config']->set('laravel-asset.loading_attribute', 'defer');
        $this->assertEquals('defer', (new JQuery)->getLoadingAttribute());
        app()['config']->set('laravel-asset.loading_attribute', null);
        $this->assertEquals(null, (new JQuery)->getLoadingAttribute());
    }
}
