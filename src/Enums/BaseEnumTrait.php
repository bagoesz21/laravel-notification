<?php

namespace Bagoesz21\LaravelNotification\Enums;

use Illuminate\Support\Arr;

trait BaseEnumTrait
{
    public static function defaultColor(): string
    {
        return 'blue';
    }

    public static function defaultIcon(): string
    {
        return 'mdi-tag';
    }

    public static function getDefaultValue(): int
    {
        return 0;
    }

    public static function getRandom($key = null, $default = null)
    {
        $random = Arr::random(self::cases());
        if (empty($default)) {
            $default = $random;
        }

        return Arr::get($random, $key, $default);
    }

    public static function toArray()
    {
        return self::cases();
    }

    public function is($value)
    {
        return $this->value == $value;
    }

    public static function getValues()
    {
        return array_map(function ($s) {
            return $s->value;
        }, self::cases());
    }
}
