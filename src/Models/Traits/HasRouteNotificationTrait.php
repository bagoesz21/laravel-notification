<?php

namespace Bagoesz21\LaravelNotification\Models\Traits;

trait HasRouteNotificationTrait
{
    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return [$this->email => $this->name];
    }

    /**
     * Route notifications for the one signal channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForOneSignal($notification)
    {
        return ['include_external_user_ids' => $this->id];
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return 'https://hooks.slack.com/services/...';
    }

    /**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForFcm($notification)
    {
        return $this->fcm_token;
    }

    /**
     * Route notifications for the Vonage channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForVonage($notification)
    {
        return $this->phone;
    }

    /**
     * Route notifications for the Nexmo channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForNexmo($notification)
    {
        return $this->phone;
    }

    /**
     * Route notifications for the Telegram channel.
     *
     * @return int
     */
    public function routeNotificationForTelegram()
    {
        return $this->telegram_user_id;
    }

    public function routeNotificationForTwitter($notification)
    {
        return [
            config('twitter.consumer.key'),
            config('twitter.consumer.secret'),
            config('twitter.access.token'),
            config('twitter.access.secret'),
        ];
    }

    public function routeNotificationForTwilio()
    {
        return $this->phone;
    }

    /**
     * Route notifications for the authy channel.
     *
     * @return int
     */
    public function routeNotificationForAuthy()
    {
        return $this->authy_id;
    }

    public function routeNotificationForWhatsapp($notification)
    {
        return $this->phone;
    }
}
