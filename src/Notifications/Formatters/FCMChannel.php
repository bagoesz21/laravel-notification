<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

/**
 * Notification to Firebase Cloud Messaging (FCM)
 *
 * @see https://laravel-notification-channels.com/fcm
 * @see https://firebase.google.com/docs/cloud-messaging/
 */
trait FCMChannel
{
    public function toFcm($notifiable)
    {
        return new FcmMessage(notification: new FcmNotification(
            title: $this->getTitle(),
            body: $this->getMessageAsPlainText(),
            //image: $this->getImageUrl()
        ));
    }
}
