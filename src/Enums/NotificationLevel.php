<?php

namespace Bagoesz21\LaravelNotification\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;

final class NotificationLevel extends BaseEnum implements LocalizedEnum
{
    const INFO = 0;
    const SUCCESS = 1;
    const WARNING = 2;
    const ERROR = 3;

    public static function colors(): array
    {
        return [
            self::INFO => 'blue',
            self::SUCCESS => 'green',
            self::WARNING => 'yellow',
            self::ERROR => 'red'
        ];
    }

    public static function defaultIcon(): string
    {
        return 'mdi-bullhorn';
    }

    public static function getDefaultValue()
    {
        return self::INFO;
    }
}
