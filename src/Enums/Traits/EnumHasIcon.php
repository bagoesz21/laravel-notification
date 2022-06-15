<?php

namespace Bagoesz21\LaravelNotification\Enums\Traits;

trait EnumHasIcon
{
    /**
     * Icon enum
     *
     * @var mixed
     */
    public $icon;

    /**
     * Get the icon for an enum value.
     *
     * @param  mixed  $value
     * @return string
     */
    public static function getIcon($value): string
    {
        return self::defaultIcon($value);
    }

    /**
     * Default icon
     *
     * @param  mixed  $value
     * @return string
     */
    private static function defaultIcon($value): string
    {
        return 'mdi-tag';
    }
}
