<?php

namespace Bagoesz21\LaravelNotification\Enums;

enum NotificationLevel: int implements Traits\HasColor
{
    use BaseEnumTrait;

    case INFO = 0;
    case SUCCESS = 1;
    case WARNING = 2;
    case ERROR = 3;

    public static function getDefaultValue(): self
    {
        return self::INFO;
    }

    public function color(): string
    {
        return match ($this) {
            self::INFO => 'blue',
            self::SUCCESS => 'green',
            self::WARNING => 'yellow',
            self::ERROR => 'red',
        };
    }
}
