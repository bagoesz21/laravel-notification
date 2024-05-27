<?php

namespace Bagoesz21\LaravelNotification\Enums\Traits;

interface HasColor
{
    public function color(): string;

    public static function defaultColor(): string;
}
