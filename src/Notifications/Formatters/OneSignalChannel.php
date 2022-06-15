<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use NotificationChannels\OneSignal\OneSignalMessage;
use NotificationChannels\OneSignal\OneSignalWebButton;

/**
 * Notification to onesignal
 *
 * @see https://documentation.onesignal.com/docs
 * @see https://laravel-notification-channels.com/onesignal/
 */
trait OneSignalChannel
{
    public function toOneSignal($notifiable)
    {
        $channel = OneSignalMessage::create()
            ->setSubject($this->getTitle())
            ->setBody($this->getMessageAsHTML())
            ->setIcon(config('site.logo.original_short.url'));

        $actions = $this->getActions();
        if(!empty($actions)){
            foreach ($actions as $key => $action) {
                $channel = $channel->setWebButton(
                    OneSignalWebButton::create('button-' . $key)
                        ->text(Arr::get($action, 'title'))
                        ->icon(Arr::get($action, 'icon'))
                        ->url(Arr::get($action, 'url'))
                );
            }
        }

        // $channel = $channel->setData();
        return $channel;
    }
}
