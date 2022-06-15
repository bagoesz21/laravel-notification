<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use NotificationChannels\WebPush\WebPushMessage;

/**
 * Notification to web push browser
 *
 * @see https://laravel-notification-channels.com/webpush/
 */
trait WebPushChannel
{
    public function toWebPush($notifiable, $notification)
    {
        $channel = (new WebPushMessage)
            ->title($this->getTitle())
            ->body($this->getMessageAsPlainText())
            ->image($this->getImageUrl());
            // ->icon('/approved-icon.png')
            // ->options(['TTL' => 1000])
            // ->data(['id' => $notification->id])
            // ->badge()
            // ->dir()
            // ->image()
            // ->lang()
            // ->renotify()
            // ->requireInteraction()
            // ->tag()
            // ->vibrate()

        $actions = $this->getActions();
        if(!empty($actions)){
            foreach ($actions as $key => $action) {
                $channel = $channel->action(Arr::get($action, 'title'), '', Arr::get($action, 'icon'));
            }
        }
        return $channel;
    }
}
