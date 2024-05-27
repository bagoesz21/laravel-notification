<?php

namespace Bagoesz21\LaravelNotification\Enums\Traits;

interface HasIcon
{
    public function icon(): string;

    public static function defaultIcon(): string;
}
