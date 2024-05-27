<?php

namespace Bagoesz21\LaravelNotification\Config\Mapper;

class NotifMapperConfig extends BaseNotifMapper
{
    public function toArray(): array
    {
        return config('notification');
    }
}
