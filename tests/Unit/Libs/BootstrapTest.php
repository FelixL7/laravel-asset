<?php


namespace FelixL7\Cdn\Tests\Unit\Libs;

use FelixL7\Cdn\CDNs\JsDelivr;
use FelixL7\Cdn\Facades\CDN;
use FelixL7\Cdn\Tests\BaseTest;

class BootstrapTest extends BaseTest
{

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('laravel-cdn.libs.bootstrap',[
            'version' => '5.0.1',
            'cdn' => JsDelivr::class,
        ]);
    }

    public function testMinimalAsyncWithoutCacheScript()
    {
        $this->assertEquals('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js?v=1" async></script>', CDN::bootstrap()->min()->async()->disableCacheWithConfigVersion()->js());
    }
}
