<?php

namespace Bagoesz21\LaravelNotification\Notifications;

use Bagoesz21\LaravelNotification\Notifications\BaseNotificationFull as BaseNotification;

class GeneralNotif extends BaseNotification
{
    public static function config(): array
    {
        return [
            'name' => trans('laravel-notification::notification.class.general.name'),
            'key' => 'GeneralNotif',
        ];
    }

    public function broadcastType()
    {
        return 'notif.general';
    }
}
