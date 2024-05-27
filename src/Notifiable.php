<?php

namespace Bagoesz21\LaravelNotification;

use Illuminate\Notifications\Notifiable as BaseNotifiable;

trait Notifiable
{
    use BaseNotifiable;

    public function receivesBroadcastNotificationsOn()
    {
        return 'user.'.$this->id;
    }

    public function routeNotificationForMail($notification)
    {
        return [$this->email => (! empty($this->name) ? $this->name : $this->username)];
    }

    public function routeNotificationForOneSignal()
    {
        return ['include_external_user_ids' => $this->id];
    }
}
