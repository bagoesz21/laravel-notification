<?php

namespace Bagoesz21\LaravelNotification\Notifications;

use Illuminate\Support\Arr;

class GeneralNotif extends BaseNotification
{
    public static function config() : array
    {
        return [
            'name' => trans('laravel-notification::notification.class.general.name'),
            'key' => 'GeneralNotif',
        ];
    }

    public function __construct()
    {
    }

    public function broadcastType()
    {
        return 'notif.general';
    }
}
