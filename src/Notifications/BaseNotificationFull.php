<?php

namespace Bagoesz21\LaravelNotification\Notifications;

use Illuminate\Support\Arr;
use Illuminate\View\View;

class BaseNotificationFull extends BaseNotificationWithFormatter
{
    use Traits\UseProseMirrorAsMessage;
}
