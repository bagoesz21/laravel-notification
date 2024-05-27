<?php

namespace Bagoesz21\LaravelNotification\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NotificationRead implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $user;

    public $notifID;

    /**
     * Create a new event instance.
     *
     * @param  \App\Models\User|null  $user
     * @param  int|array  $notifID
     * @return void
     */
    public function __construct($user, $notifID)
    {
        $this->user = $user;
        $this->notifID = is_array($notifID) ? $notifID : [$notifID];

        $this->dontBroadcastToCurrentUser();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
