<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use NotificationChannels\Webhook\WebhookMessage;

/**
 * Notification to webhook
 *
 * @see https://laravel-notification-channels.com/webhook
 */
trait WebHookChannel
{
    public function toWebhook($notifiable)
    {
        return WebhookMessage::create()
            ->data([
            ])
            ->userAgent("Custom-User-Agent")
            ->header('X-Custom', 'Custom-Header');
    }
}
