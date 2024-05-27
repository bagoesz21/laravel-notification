<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use NotificationChannels\PusherPushNotifications\PusherMessage;

/**
 * Notification to pusher
 *
 * @see https://pusher.com/docs/beams
 * @see https://laravel-notification-channels.com/pusher-push-notifications
 */
trait PusherChannel
{
    public function toPushNotification($notifiable)
    {
        $channel = PusherMessage::create()
            ->title($this->getTitle())
            ->body($this->getMessageAsPlainText());

        // ->link()
        // ->iOS()
        // ->android()
        // ->badge(1)
        // ->sound('success')
        return $channel;
    }
}
