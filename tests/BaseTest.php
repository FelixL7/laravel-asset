<?php

namespace FelixL7\Cdn\Tests;

use FelixL7\Cdn\CDNs\Cdnjs;
use FelixL7\Cdn\CDNServiceProvider;
use Orchestra\Testbench\TestCase;

class BaseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->resetCdnConfig();
    }

    protected function getPackageProviders($app)
    {
        return [
            CDNServiceProvider::class,
        ];
    }

    protected function resetCdnConfig() {
        app()['config']->set('laravel-cdn.cdn', Cdnjs::class);
    }
}
