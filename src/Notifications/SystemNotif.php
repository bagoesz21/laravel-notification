<?php

namespace Bagoesz21\LaravelNotification\Notifications;

use Illuminate\Support\Arr;

class SystemNotif extends BaseNotification
{
    public function __construct()
    {
    }

    public function broadcastType()
    {
        return 'notif.system';
    }
}
