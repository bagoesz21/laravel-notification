<?php

namespace Bagoesz21\LaravelNotification\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;

final class DeliveryTimeStatus extends BaseEnum implements LocalizedEnum
{
    const IMMEDIATELY = 0;
    const SCHEDULE = 1;

    public static function colors(): array
    {
        return [
            self::IMMEDIATELY => 'red',
            self::SCHEDULE => 'green',
        ];
    }

    public static function icons(): array
    {
        return [
            self::IMMEDIATELY => 'mdi-alarm-check',
            self::SCHEDULE => 'mdi-alarm-snooze',
        ];
    }

    public static function getDefaultValue()
    {
        return self::IMMEDIATELY;
    }
}
