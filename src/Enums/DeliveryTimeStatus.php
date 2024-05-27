<?php

namespace Bagoesz21\LaravelNotification\Enums;

enum DeliveryTimeStatus: int implements Traits\HasColor
{
    use BaseEnumTrait;

    case IMMEDIATELY = 0;
    case SCHEDULE = 1;

    public static function getDefaultValue(): self
    {
        return self::IMMEDIATELY;
    }

    public function color(): string
    {
        return match ($this) {
            self::IMMEDIATELY => 'green',
            self::SCHEDULE => 'red',
        };
    }
}
