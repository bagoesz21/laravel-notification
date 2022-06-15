<?php

namespace Bagoesz21\LaravelNotification\Channels;

use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Notification;
use Bagoesz21\LaravelNotification\Enums\NotificationLevel;

/**
 * Extend database channel for notification
 */
class DatabaseChannel extends IlluminateDatabaseChannel
{
    /**
     * Build an array payload for the DatabaseNotification Model.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array
     */
    protected function buildPayload($notifiable, Notification $notification)
    {
        $level = $notification->level;
        if(!($notification->level instanceof NotificationLevel)){
            $levelValue = empty($level) ? NotificationLevel::getDefaultValue(): $level;
            $level = NotificationLevel::fromValue($levelValue);
        }

        return [
            'id'        => $notification->id,
            'type'      => get_class($notification),
            'level'     => optional($level)->value,
            'title'     => $notification->title,
            'message'   => $notification->message,
            'image'     => $notification->image,
            'data'      => $this->getData($notifiable, $notification),
            'read_at'   => null,
        ];
    }
}
