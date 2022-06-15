<?php

namespace Bagoesz21\LaravelNotification\Notifications\Formatters;

use Illuminate\Support\Arr;
use Illuminate\Notifications\Messages\BroadcastMessage;

/**
 * Notification to broadcast
 *
 * @see https://laravel.com/docs/8.x/notifications#formatting-broadcast-notifications
 */
trait BroadcastChannel
{
    /**
     * Init broadcast
     *
     * @return self
     */
    public function initBroadcast()
    {
        $this->setSelectedChannel('broadcast');
        return $this;
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->getData('id'),
            'title' => $this->getTitle(),
            'level' => $this->getLevel(),
            'image' => $this->getData('image'),
            'database_notif' => $this->isNotifVia('database')
        ]);
    }

    public function broadcastType()
    {
        return 'notif.base';
    }
}
