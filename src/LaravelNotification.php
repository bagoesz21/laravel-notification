<?php

namespace Bagoesz21\LaravelNotification;

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
        $this->app->instance(\Illuminate\Notifications\Channels\DatabaseChannel::class, new
        Channels\DatabaseChannel());
    }
}
