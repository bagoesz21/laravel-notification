<?php

namespace Bagoesz21\LaravelNotification\Enums\Traits;

use Illuminate\Support\Arr;

trait EnumHasColor
{
    /**
     * Color enum
     *
     * @var mixed
     */
    public $color;

    /**
     * List enum colors
     *
     * @return array
     */
    public static function colors(): array
    {
        return [];
    }

    /**
     * Get the color for an enum value.
     *
     * @param  mixed  $value
     * @return string
     */
    public static function getColor($value): string
    {
        $class = get_called_class();
        if(empty($class::colors()))return $class::defaultColor();
        return Arr::get($class::colors(), $value, $class::defaultColor());
    }

    /**
     * Default color
     *
     * @return string
     */
    public static function defaultColor(): string
    {
        return 'blue';
    }
}
