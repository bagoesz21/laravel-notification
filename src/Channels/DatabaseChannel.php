<?php

namespace Bagoesz21\LaravelNotification\Channels;

use Bagoesz21\LaravelNotification\Enums\NotificationLevel;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Notifications\Notification;

/**
 * Extend database channel for notification
 */
class DatabaseChannel extends IlluminateDatabaseChannel
{
    /**
     * Build an array payload for the DatabaseNotification Model.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    protected function buildPayload($notifiable, Notification $notification)
    {
        $level = $notification->level;
        if (! ($notification->level instanceof NotificationLevel)) {
            $levelValue = empty($level) ? NotificationLevel::getDefaultValue() : $level;
            $level = NotificationLevel::from($levelValue);
        }

        return [
            'id' => $notification->id,
            'type' => get_class($notification),
            'level' => optional($level)->value,
            'title' => $notification->title,
            'message' => $notification->message,
            'image' => $notification->image,
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
        ];
    }
}
