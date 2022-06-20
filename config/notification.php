<?php

use Illuminate\Support\Arr;

/*
|--------------------------------------------------------------------------
| Notification Config
|--------------------------------------------------------------------------
| Enabled: enable channel notification
| Name: name channel notification
| Value: alias channel (for store notification)
| Default: default notif allowed
| Class: channel class for notification
| Mandatory: required / important / always allowed channel
| queue_name: set specific queue for send notif
| Description: description about channel
| icon: icon notification
| utm: Urchin Tracking Module (UTM) for notification
|
| @see https://laravel.com/docs/notifications/
| @see https://laravel-notification-channels.com/
*/
$packageName = "laravel-notification";
$defaultQueue = env('NOTIF_QUEUE_NAME', 'default');
$defaultQueueConnection = env('NOTIF_QUEUE_CONNECTION', env('QUEUE_CONNECTION', 'redis'));

$defaultUTM = [
    [
        'key' => 'source',
        'value' => 'notification'
    ],
];
$defaultChannel = [
    'enabled' => false,
    'name' => null,
    'value' => null,
    'default' => false,
    'class' => null,
    'mandatory' => false,
    'description' => '',
    'queue_name' => $defaultQueue,
    'queue_connection' => $defaultQueueConnection,
    'delay' => null, //in minutes
    'icon' => 'mdi-bell',
    'utm' => $defaultUTM
];

$channels = [
    'database' => [
        'enabled'       => true,
        'name'          => 'Database',
        'value'         => 'database',
        'class'         => 'database',
        'mandatory'     => true,
        'description'   => 'Notifikasi disimpan didatabase untuk dibaca user kemudian hari',
        'icon'          => 'mdi-database',
        'utm'           => array_merge_recursive($defaultUTM, [
            [
                'key' => 'medium',
                'value' => 'database'
            ],
        ])
    ],
    'broadcast' => [
        'enabled'       => true,
        'name'          => 'Realtime',
        'value'         => 'broadcast',
        'default'       => true,
        'class'         => 'broadcast',
        'description'   => 'Notifikasi dikirim secara realtime di web',
        'icon'          => 'mdi-broadcast',
        'utm'           => false
    ],
    'mail' => [
        'enabled'       => true,
        'name'          => 'E-mail',
        'value'         => 'mail',
        'class'         => 'mail',
        'description'   => 'Notifikasi dikirim ke e-mail user',
        'icon'          => 'mdi-email',
        'mailer'        => env('MAIL_MAILER', 'smtp'),
        'utm'           => array_merge_recursive($defaultUTM, [
            [
                'key' => 'medium',
                'value' => 'email'
            ],
        ])
    ],
    'web push' => [
        'name'          => 'Web Push',
        'value'         => 'web push',
        'class'         => \NotificationChannels\WebPush\WebPushChannel::class,
        'description'   => 'Notifikasi dikirim melalui browser user yang sudah berlangganan notifikasi'
    ],
    'one signal' => [
        'enabled'       => true,
        'name'          => 'One Signal',
        'value'         => 'one signal',
        'class'         => \NotificationChannels\OneSignal\OneSignalChannel::class,
        'description'   => 'Notifikasi dengan one signal',
        'icon'          => 'mdi-broadcast',
    ],
    'pusher' => [
        'name'          => 'Pusher',
        'value'         => 'pusher',
        'class'         => \NotificationChannels\PusherPushNotifications\PusherChannel::class,
        'description'   => 'Notifikasi dengan pusher'
    ],
    'fcm' => [
        'name'          => 'Firebase Cloud Messaging',
        'value'         => 'fcm',
        'class'         => \NotificationChannels\Fcm\FcmChannel::class,
        'description'   => 'Notifikasi dengan firebase cloud messaging google',
        'icon'          => 'mdi-firebase',
    ],
    'telegram' => [
        'name'          => 'Telegram',
        'value'         => 'telegram',
        'class'         => \NotificationChannels\Telegram\TelegramChannel::class,
        'description'   => 'Notifikasi dengan telegram',
    ],
    'twilio' => [
        'name'          => 'Twilio',
        'value'         => 'twilio',
        'class'         => \NotificationChannels\Twilio\TwilioChannel::class,
        'description'   => 'Notifikasi dengan twilio'
    ],
    'authy' => [
        'name'          => 'Authy',
        'value'         => 'authy',
        'class'         => \NotificationChannels\Authy\AuthyChannel::class,
        'description'   => 'Notifikasi dengan authy'
    ],
    'nexmo' => [
        'name'          => 'Nexmo Vonage',
        'value'         => 'nexmo',
        'class'         => \Illuminate\Notifications\Channels\VonageSmsChannel::class,
        'description'   => 'Notifikasi dengan nexmo vonage'
    ],
    'webhook' => [
        'name'          => 'Webhook',
        'value'         => 'webhook',
        'class'         => \NotificationChannels\Webhook\WebhookChannel::class,
        'description'   => 'Notifikasi dengan webhook',
        'icon'          => 'mdi-webhook',
    ],
];

$channels = array_map(function($channel) use ($defaultChannel){
    return array_merge($defaultChannel, $channel);
}, $channels);

$enabledChannels = array_filter($channels, function($channel) {
    return $channel['enabled'];
});

return [
    'connection' => $defaultQueueConnection,
    'queue_name' => $defaultQueue,
    'after_commit' => (bool) env('NOTIF_AFTER_COMMIT', true),
    'locale' => config('app.locale'),
    'all' => $channels,
    'channels' => $enabledChannels,
    'mandatory_channels' => Arr::pluck(Arr::where($enabledChannels, function ($channel){
        return ($channel['mandatory'] === true);
    }), 'value'),
    'default_channels' => Arr::pluck(Arr::where($enabledChannels, function ($channel){
        return ($channel['default'] === true);
    }), 'value'),

    'utm' => $defaultUTM,

    'models' => [
        'notification' => Bagoesz21\LaravelNotification\Models\Notification::class,
    ]
];
