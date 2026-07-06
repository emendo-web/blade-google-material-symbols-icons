<?php

declare(strict_types=1);

namespace Tests;

use BladeUI\Icons\BladeIconsServiceProvider;
use Kienso\BladeMaterialSymbols\BladeMaterialSymbolsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            BladeIconsServiceProvider::class,
            BladeMaterialSymbolsServiceProvider::class,
        ];
    }
}
