<?php

use Illuminate\Support\Arr;
use Bagoesz21\LaravelNotification\Config\NotifConfigBuilder;

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
        'icon'          => 'mdi-broadcast',
        'utm'           => false
    ],
    'mail' => [
        'enabled'       => true,
        'name'          => 'E-mail',
        'value'         => 'mail',
        'class'         => 'mail',
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
    ],
    'one signal' => [
        'enabled'       => true,
        'name'          => 'One Signal',
        'value'         => 'one signal',
        'class'         => \NotificationChannels\OneSignal\OneSignalChannel::class,
        'icon'          => 'mdi-broadcast',
    ],
    'pusher' => [
        'name'          => 'Pusher',
        'value'         => 'pusher',
        'class'         => \NotificationChannels\PusherPushNotifications\PusherChannel::class,
    ],
    'fcm' => [
        'name'          => 'Firebase Cloud Messaging',
        'value'         => 'fcm',
        'class'         => \NotificationChannels\Fcm\FcmChannel::class,
        'icon'          => 'mdi-firebase',
    ],
    'telegram' => [
        'name'          => 'Telegram',
        'value'         => 'telegram',
        'class'         => \NotificationChannels\Telegram\TelegramChannel::class,
    ],
    'twilio' => [
        'name'          => 'Twilio',
        'value'         => 'twilio',
        'class'         => \NotificationChannels\Twilio\TwilioChannel::class,
    ],
    'authy' => [
        'name'          => 'Authy',
        'value'         => 'authy',
        'class'         => \NotificationChannels\Authy\AuthyChannel::class,
    ],
    'nexmo' => [
        'name'          => 'Nexmo Vonage',
        'value'         => 'nexmo',
        'class'         => \Illuminate\Notifications\Channels\VonageSmsChannel::class,
    ],
    'webhook' => [
        'name'          => 'Webhook',
        'value'         => 'webhook',
        'class'         => \NotificationChannels\Webhook\WebhookChannel::class,
        'icon'          => 'mdi-webhook',
    ],
];

$others = [
    'tables' => [
        'notification' => [
            'model' => Bagoesz21\LaravelNotification\Models\Notification::class,

            /**
             * Default null, for default table name
             */
            'table_name' => null,
        ],
        'notification_template' => [
            /**
             * Set enabled true, if you want log notification
             */
            'enabled' => false,
            'model' => Bagoesz21\LaravelNotification\Models\NotificationTemplate::class,

            /**
             * Default null, for default table name
             */
            'table_name' => null,
        ],
        'notification_log' => [
            /**
             * Set enabled true, if you want log notification
             */
            'enabled' => false,
            'model' => Bagoesz21\LaravelNotification\Models\NotificationLog::class,

            /**
             * Default null, for default table name
             */
            'table_name' => null,
        ]
    ],

    'notifications' => [
        'general' => Bagoesz21\LaravelNotification\Notifications\GeneralNotif::class,
        'system' => Bagoesz21\LaravelNotification\Notifications\SystemNotif::class,
    ],

    /**
     * @see https://laravel.com/docs/eloquent-relationships#custom-polymorphic-types
     */
    'morph' => [
        'enabled' => true,
        'map' => [
            'user' => 'App\Models\User',
        ]
    ],

    /**
     * If you want custom config notif, before notif initialize.
     * For example if you need load stored config in database,
     * load config and mapping config with mapper config class.
     * Default: load from config.
     */
    'mapper' => Bagoesz21\LaravelNotification\Config\Mapper\NotifMapperConfig::class,
];

return NotifConfigBuilder::make()
    ->queueName($defaultQueue)
    ->queueConnection($defaultQueueConnection)
    ->afterCommit((bool) env('NOTIF_AFTER_COMMIT', true))
    ->locale(config('app.locale'))
    ->localize(false)
    ->channels($channels)
    ->other($others)
    ->build();
