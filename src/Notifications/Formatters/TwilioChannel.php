<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use NotificationChannels\Twilio\TwilioSmsMessage;

/**
 * Notification to twilio
 *
 * @see https://laravel-notification-channels.com/twilio
 * @see https://documentation.twilio.com/docs
 */
trait TwilioChannel
{
    public function toTwilio($notifiable)
    {
        return (new TwilioSmsMessage())
            ->content($this->getMessageAsPlainText());
    }
}
