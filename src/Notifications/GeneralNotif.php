<?php

namespace Bagoesz21\LaravelNotification\Notifications;

use Illuminate\Support\Arr;

class GeneralNotif extends BaseNotification
{
    public function __construct()
    {
    }

    public function broadcastType()
    {
        return 'notif.general';
    }
}
