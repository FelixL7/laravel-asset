<?php

namespace FelixL7\Resource\Tests;

use FelixL7\Resource\CDNs\Cdnjs;
use FelixL7\Resource\ResourceServiceProvider;
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
            ResourceServiceProvider::class,
        ];
    }

    protected function resetCdnConfig() {
        app()['config']->set('laravel-cdn.cdn', Cdnjs::class);
    }
}
