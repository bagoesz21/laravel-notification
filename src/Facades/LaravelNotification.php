<?php

namespace Bagoesz21\LaravelNotification\Facades;

use Illuminate\Support\Facades\Facade;

class LaravelNotification extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-notification';
    }
}
