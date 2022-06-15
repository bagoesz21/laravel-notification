<?php

namespace Bagoesz21\LaravelNotification\Enums\Traits;

trait EnumHasColor
{
    /**
     * Color enum
     *
     * @var mixed
     */
    public $color;

    /**
     * Get the color for an enum value.
     *
     * @param  mixed  $value
     * @return string
     */
    public static function getColor($value): string
    {
        return self::defaultColor($value);
    }

    /**
     * Default color
     *
     * @param  mixed  $value
     * @return string
     */
    private static function defaultColor($value): string
    {
        return 'blue';
    }
}
