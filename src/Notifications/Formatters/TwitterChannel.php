<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use NotificationChannels\Twitter\TwitterStatusUpdate;

/**
 * Notification to twitter
 *
 * @see https://laravel-notification-channels.com/twitter
 */
trait TwitterChannel
{
    public function toTwitter($notifiable)
    {
        $channel = new TwitterStatusUpdate($this->getMessageAsPlainText());
        // $channel->withImage([
        // ]);

        return $channel;
    }
}
