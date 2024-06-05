<?php

namespace Bagoesz21\LaravelNotification;

use Bagoesz21\LaravelNotification\Models\Traits\HasRouteNotificationTrait;
use Illuminate\Notifications\Notifiable as BaseNotifiable;

trait Notifiable
{
    use BaseNotifiable, HasRouteNotificationTrait;

    public function receivesBroadcastNotificationsOn()
    {
        return 'user.'.$this->id;
    }
}
