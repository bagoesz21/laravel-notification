<?php

namespace Bagoesz21\LaravelNotification\Config\Mapper;

use Illuminate\Support\Arr;

class NotifMapperConfig extends BaseNotifMapper
{
    public function toArray(): array
    {
        return config('notification');
    }
}
