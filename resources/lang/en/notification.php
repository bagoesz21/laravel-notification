<?php

$table = 'notification';
$tableSchedule = "$table schedule";
$tableLog = "$table log";
$tableTemplate = "$table template";

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Locale
    |--------------------------------------------------------------------------
    |
    |
    */

    'locale' => $table,

    'id' => "$table id",

    'title' => "$table title",
    'message' => "$table message",
    'image' => "$table image",
    'external_url' => "external url",
    'data' => "$table data",
    'type' => "$table type",

    'channel' => [
        'send' => 'send notification via'
    ],

    'channels' => [
        'database' => [
            'name' => 'database',
            'description' => 'Notifications are stored in the database for the user to read later'
        ],
        'broadcast' => [
            'name' => 'realtime',
            'description' => 'Notifications are sent in real time on the web'
        ],
        'mail' => [
            'name' => 'e-mail',
            'description' => 'Notifications are sent to the user`s email'
        ],
        'web_push' => [
            'name' => 'web push',
            'description' => 'Notifications are sent via the browser of users who have subscribed to notifications'
        ],
        'one_signal' => [
            'name' => 'one signal',
            'description' => 'Notification with one signal'
        ],
        'pusher' => [
            'name' => 'pusher',
            'description' => 'Notification with pusher'
        ],
        'fcm' => [
            'name' => 'Firebase cloud messaging',
            'description' => 'Notification with firebase cloud messaging google'
        ],
        'whatsapp' => [
            'name' => 'whatsapp',
            'description' => 'Notification with whatsapp'
        ],
        'telegram' => [
            'name' => 'telegram',
            'description' => 'Notification with telegram'
        ],
        'twilio' => [
            'name' => 'twilio',
            'description' => 'Notification with twilio'
        ],
        'authy' => [
            'name' => 'authy',
            'description' => 'Notification with authy'
        ],
        'nexmo' => [
            'name' => 'nexmo',
            'description' => 'Notification with nexmo vonage'
        ],
        'webhook' => [
            'name' => 'webhook',
            'description' => 'Notification with webhook'
        ]
    ],

    'delivery' => [
        'schedule_send' => "schedule send $table",
        'at' => 'delivery notification at'
    ],

    'template' => [
        'locale' => $tableTemplate,

        'id' => "$tableSchedule id",
        'title' => "$tableSchedule title",
        'message' => "$tableSchedule message",
    ],

    'schedule' => [
        'locale' => $tableSchedule,

        'id' => "$tableSchedule id",
        'target_type' => 'target type',
        'target' => 'target',
        'notification_type' => 'notification type',
        'send_at' => 'send at',
        'reschedule_at' => 'reschedule at',
        'cancelled_at' => 'cancelled at'
    ],

    'log' => [
        'locale' => $tableLog,

        'id' => "$tableLog id",
        'notification_type' => "$tableLog type",
        'send_at' => 'send at',
        'failed_at' => 'failed at',
        'opened_at' => 'opened at',
    ],

    'class' => [
        'general' => [
            'name' => "general $table",
        ],
        'system' => [
            'name' => "system $table",
        ]
    ]

];
