<?php

namespace Bagoesz21\LaravelNotification;

use Illuminate\Support\Arr;
use Bagoesz21\LaravelNotification\Helpers\NotifConfig;

class LaravelNotification
{
    /**
     * @return self
     */
    public static function make()
    {
        return (new self());
    }

    public function init()
    {
        $this->morphMap();
        $this->app->instance(\Illuminate\Notifications\Channels\DatabaseChannel::class, new
        Channels\DatabaseChannel());
    }

    /**
     * @return \Bagoesz21\LaravelNotification\Models\Notification
     */
    public function notifModelClass()
    {
        return app(config('notification.models.notification'));
    }

    /**
     * @return array
     */
    public function morphMap()
    {
        if(!config('notification.morph.enabled'))return [];
        return \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap(config('notification.morph_map'));
    }

    public function getConfig()
    {
        return NotifConfig::make()->translatedConfig(config('notification'));
    }

    /**
     * @param string $notifKey
     * @return \Bagoesz21\LaravelNotification\Models\Notification
     */
    public function notifClass($notifKey = 'system')
    {
        return app(config("notification.notifications.$notifKey", config("notification.notifications.system")));
    }
}
