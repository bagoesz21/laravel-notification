# LaravelNotification

Laravel Notification Wrapper

## Installation

Via Composer

``` bash
$ composer require bagoesz21/laravel-notification
```

Publish config : 
```php
php artisan vendor:publish
```

Add this code on boot in AppServiceProvider
```
\Bagoesz21\LaravelNotification\Facades\LaravelNotification::make()->init();
```

Setting .env code : (optional)
```
NOTIF_QUEUE_NAME=default
NOTIF_QUEUE_CONNECTION=redis
NOTIF_AFTER_COMMIT=true
```

## Requirements
* PHP >= 7.3 | 8.0
* Laravel >= 7

## Usage

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
