<?php

namespace FelixL7\Asset\Tests;

use FelixL7\Asset\CDNs\Cdnjs;
use FelixL7\Asset\AssetServiceProvider;
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
            AssetServiceProvider::class,
        ];
    }

    protected function resetCdnConfig() {
        app()['config']->set('laravel-asset.cdn', Cdnjs::class);
    }
}
