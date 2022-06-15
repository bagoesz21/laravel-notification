<?php

namespace Bagoesz21\LaravelNotification\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
final class NotificationLevel extends BaseEnum implements LocalizedEnum
{
    const INFO = 0;
    const SUCCESS = 1;
    const WARNING = 2;
    const ERROR = 3;

    public static function getColor($value): string
    {
        switch ($value) {
            case self::INFO:
                return 'blue';
            case self::SUCCESS:
                return 'green';
            case self::WARNING:
                return 'yellow';
            case self::ERROR:
                return 'red';
            default:
                return self::getKey($value);
        }

        return parent::getColor($value);
    }

    public static function getIcon($value): string
    {
        return 'mdi-bullhorn';
    }

    public static function getDefaultValue()
    {
        return self::INFO;
    }
}
