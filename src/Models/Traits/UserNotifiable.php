<?php

namespace Bagoesz21\LaravelNotification\Models\Traits;

use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\RoutesNotifications;

trait UserNotifiable
{
    use HasDatabaseNotifications, RoutesNotifications;

    /**
     * Get the entity's notifications.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'asc');
    }
}
