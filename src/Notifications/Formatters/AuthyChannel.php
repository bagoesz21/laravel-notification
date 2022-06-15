<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use NotificationChannels\Authy\AuthyMessage;

/**
 * Notification to authy
 *
 * @see https://laravel-notification-channels.com/authy
 */
trait AuthyChannel
{

    public function toAuthy($notifiable)
    {
        $channel = AuthyMessage::create()->method('sms');

        $channel->actionMessage($this->getMessageAsPlainText());

        return $channel;
    }
}
