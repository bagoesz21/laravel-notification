<?php

namespace Bagoesz21\LaravelNotification;

use Bagoesz21\LaravelNotification\Config\NotifConfig;
use Illuminate\Support\Facades\App;

class LaravelNotification
{
    protected $mapper;

    protected $config;

    /**
     * @return self
     */
    public static function make()
    {
        return new self();
    }

    public function __construct()
    {
        $mapperClass = config('notification.mapper');
        $this->setMapper($mapperClass);
        $this->config = NotifConfig::make($mapperClass);
    }

    public function init()
    {
        $this->morphMap();
        App::instance(\Illuminate\Notifications\Channels\DatabaseChannel::class, new Channels\DatabaseChannel());
    }

    public function setMapper($mapper)
    {
        $this->mapper = app($mapper);

        return $this;
    }

    /**
     * @return array
     */
    public function morphMap()
    {
        if (! $this->config->get('morph.enabled')) {
            return [];
        }

        return \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap($this->config->get('morph.map'));
    }

    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return \Bagoesz21\LaravelNotification\Models\Notification
     */
    public function notifModelClass()
    {
        return app($this->config->get('tables.notification.models'));
    }

    /**
     * @return \Bagoesz21\LaravelNotification\Models\NotificationLog
     */
    public function notifLogModelClass()
    {
        return app($this->config->get('tables.notification_log.models'));
    }

    /**
     * @return \Bagoesz21\LaravelNotification\Models\NotificationTemplate
     */
    public function notifTemplateModelClass()
    {
        return app($this->config->get('tables.notification_template.models'));
    }

    /**
     * @param  string  $notifKey
     * @return \Bagoesz21\LaravelNotification\Models\Notification
     */
    public function notifClass($notifKey = 'system')
    {
        return app($this->config->get("notifications.$notifKey"),
            $this->config->get('notifications.system'));
    }
}
