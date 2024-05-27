<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use NotificationChannels\Messagebird\MessagebirdMessage;

/**
 * Notification to message bird
 *
 * @see https://laravel-notification-channels.com/messagebird/
 */
trait MessageBirdChannel
{
    public function toMessagebird($notifiable)
    {
        $channel = (new MessagebirdMessage($this->getMessageAsPlainText()));
        $channel = $channel->setReference($notifiable->id);

        return $channel;
    }
}
