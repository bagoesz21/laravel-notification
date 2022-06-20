<?php

namespace Bagoesz21\LaravelNotification\Notifications;

use Illuminate\Support\Arr;
use Illuminate\View\View;

class BaseNotificationWithFormatter extends BaseNotification
{
    use Formatters\MailChannel;
    use Formatters\BroadcastChannel;
    use Formatters\OneSignalChannel;
}
