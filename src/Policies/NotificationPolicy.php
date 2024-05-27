<?php

namespace Bagoesz21\LaravelNotification\Policies;

use App\Models\Notification;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy extends BasePolicy
{
    use HandlesAuthorization;

    protected $roleGroup = 'notification';

    protected $model = Notification::class;
}
