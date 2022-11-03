<?php

namespace Bagoesz21\LaravelNotification\Enums\Traits;

use Illuminate\Support\Arr;

trait EnumHasIcon
{
    /**
     * Icon enum
     *
     * @var mixed
     */
    public $icon;

    /**
     * List enum icons
     *
     * @return array
     */
    public static function icons(): array
    {
        return [];
    }

    /**
     * Get the icon for an enum value.
     *
     * @param  mixed  $value
     * @return string
     */
    public static function getIcon($value): string
    {
        $class = get_called_class();
        if(empty($class::icons()))return $class::defaultIcon();
        return Arr::get($class::icons(), $value, $class::defaultIcon());
    }

    /**
     * Default icon
     *
     * @return string
     */
    public static function defaultIcon(): string
    {
        return 'mdi-tag';
    }
}
