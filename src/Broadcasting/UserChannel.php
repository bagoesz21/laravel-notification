<?php

namespace Bagoesz21\LaravelNotification\Broadcasting;

use App\Models\User;

class UserChannel
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param  \App\Models\User  $user
     * @param   String $userID
     * @return array|bool
     */
    public function join(User $user, $userID)
    {
        return (int) $user->id === (int) $userID;
    }
}
