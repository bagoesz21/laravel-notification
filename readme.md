# LaravelNotification

Laravel Notification Wrapper

## Installation

Via Composer

``` bash
composer require bagoesz21/laravel-notification
```

Publish config : 
```php
php artisan vendor:publish
```

Add this code on boot in AppServiceProvider
```php
\Bagoesz21\LaravelNotification\Facades\LaravelNotification::make()->init();
```

Setting .env code : (optional)
```env
NOTIF_QUEUE_NAME=default
NOTIF_QUEUE_CONNECTION=redis
NOTIF_AFTER_COMMIT=true
```

## Requirements
* PHP >= 8.0
* Laravel >= 9

## Usage
#### Send Notif To User

```php
use Bagoesz21\LaravelNotification\Notifications\GeneralNotif;
use Bagoesz21\LaravelNotification\Enums\NotificationLevel;
use App\Models\User;

$channels = [
    'database'
];
$user = User::first();
$notif = GeneralNotif::create()
	->setLevel(NotificationLevel::INFO)
	->setTitle('Title')
	->setMessage('message')
	->setChannels($channels)
	->setActions()
$user->notify($notif);
```

#### Batch Send Notif

```php
use App\Models\User;
use Bagoesz21\LaravelNotification\Enums\NotificationLevel;
use Bagoesz21\LaravelNotification\Enums\DeliveryTimeStatus;
use Bagoesz21\LaravelNotification\Services\NotificationService;
use Bagoesz21\LaravelNotification\Notifications\GeneralNotif;
use Bagoesz21\LaravelNotification\Notifications\Actions\ButtonAction;

$notif = NotificationService::make();

$userQry = User::query();
$channels = [
    'database'
];

$actions = [
	ButtonAction::create(route('home'), 'Download')
	->icon('mdi-download')
	->tooltip('Download')
	->color('primary')
];
$laravelNotif = GeneralNotif::create()
    ->setLevel(NotificationLevel::INFO)
    ->setTitle('Title Notif')
    ->setMessage('message')
    ->setChannels($channels)
    ->setActions($actions);

$batchConfig = [
	'name' => 'Send Notif To User',
	'connection' => 'redis',
	'queue' => 'default',
	'allow_failures' => true,
];

$deliveryConfig = [
    'notif' => DeliveryTimeStatus::IMMEDIATELY,
    'delivery_at' => null
];
$batch = $notif->batchSendNotif($userQry, $laravelNotif, $deliveryConfig, $batchConfig);
```

#### Mark Notifications as Read

```php
use Bagoesz21\LaravelNotification\Services\NotificationService;

$notifId = 1;
NotificationService::make()->markAsRead($notifId);
```

#### Mark All Notifications as Read

```php
use Bagoesz21\LaravelNotification\Services\NotificationService;

NotificationService::make()->markAllRead();
```

#### Mark Notifications as UnRead

```php
use Bagoesz21\LaravelNotification\Services\NotificationService;

$notifId = 1;
NotificationService::make()->markAsUnRead($notifId);
```

#### Delete Notifications

```php
use Bagoesz21\LaravelNotification\Services\NotificationService;

$notifId = 1;
NotificationService::make()->delete($notifId);
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Credits

- [BEEP][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.
