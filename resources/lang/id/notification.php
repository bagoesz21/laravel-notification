<?php

$table = 'notifikasi';
$tableSchedule = "jadwal $table";
$tableTemplate = "template $table";

return [
    /*
    |--------------------------------------------------------------------------
    | Notification Locale
    |--------------------------------------------------------------------------
    |
    |
    */

    'locale' => $table,

    'id' => "id $table",
    'title' => "judul $table",
    'message' => "pesan $table",
    'image' => "gambar $table",
    'external_url' => "url eksternal",
    'data' => "data $table",
    'type' => "tipe $table",

    'channel' => [
        'send' => 'kirim notifikasi via'
    ],

    'channels' => [
        'database' => [
            'name' => 'database',
            'description' => 'Notifikasi disimpan didatabase untuk dibaca user kemudian hari'
        ],
        'broadcast' => [
            'name' => 'realtime',
            'description' => 'Notifikasi dikirim secara realtime di web'
        ],
        'mail' => [
            'name' => 'surel',
            'description' => 'Notifikasi dikirim ke e-mail user'
        ],
        'web_push' => [
            'name' => 'web push',
            'description' => 'Notifikasi dikirim melalui browser user yang sudah berlangganan notifikasi'
        ],
        'one_signal' => [
            'name' => 'one signal',
            'description' => 'Notifikasi dengan one signal'
        ],
        'pusher' => [
            'name' => 'pusher',
            'description' => 'Notifikasi dengan pusher'
        ],
        'fcm' => [
            'name' => 'Firebase cloud messaging',
            'description' => 'Notifikasi dengan firebase cloud messaging google'
        ],
        'whatsapp' => [
            'name' => 'whatsapp',
            'description' => 'Notifikasi dengan whatsapp'
        ],
        'telegram' => [
            'name' => 'telegram',
            'description' => 'Notifikasi dengan telegram'
        ],
        'twilio' => [
            'name' => 'twilio',
            'description' => 'Notifikasi dengan twilio'
        ],
        'authy' => [
            'name' => 'authy',
            'description' => 'Notifikasi dengan authy'
        ],
        'nexmo' => [
            'name' => 'nexmo',
            'description' => 'Notifikasi dengan nexmo vonage'
        ],
        'webhook' => [
            'name' => 'webhook',
            'description' => 'Notifikasi dengan webhook'
        ]
    ],

    'delivery' => [
        'schedule_send' => 'jadwal kirim notifikasi',
        'at' => 'kirim notifikasi pada'
    ],

    'template' => [
        'locale' => $tableTemplate,

        'id' => "id $tableSchedule",
        'title' => "judul $tableSchedule",
        'message' => "pesan $tableSchedule",
    ],

    'schedule' => [
        'locale' => $tableSchedule,

        'id' => "id $tableSchedule",
        'target_type' => 'tipe sasaran',
        'target' => 'sasaran',
        'notification_type' => "tipe $table",
        'send_at' => 'dikirim pada',
        'reschedule_at' => 'dikirim ulang pada',
        'cancelled_at' => 'dibatalkan pada'
    ],

];
