<?php

namespace Bagoesz21\LaravelNotification\Notifications;

class BaseNotificationWithFormatter extends BaseNotification
{
    use Formatters\BroadcastChannel;
    use Formatters\MailChannel;
    use Formatters\OneSignalChannel;
}
