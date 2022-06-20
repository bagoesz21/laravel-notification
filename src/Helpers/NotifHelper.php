<?php

namespace Bagoesz21\LaravelNotification\Helpers;

use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

use Bagoesz21\LaravelNotification\LaravelNotification;
use App\Models\User;
use Bagoesz21\LaravelNotification\Notifications\SystemNotif;
use Bagoesz21\LaravelNotification\Jobs\SystemNotifJob;
use Illuminate\Support\Facades\Auth;

class NotifHelper
{
    public static function getNotifModelClass()
    {
        return LaravelNotification::make()->notifModelClass();
    }

    public static function getNotifClass()
    {
        return LaravelNotification::make()->notifClass();
    }

    /**
     * Send Notif immediately / schedule
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @param \App\Models\User $user
     * @param \Carbon\Carbon|null $sentAt
     * @return void
     */
    public static function send(Notification $notification, User $user, Carbon $sentAt = null)
    {
        if (empty($sentAt)) {
            //send notification immediately
            NotificationFacade::sendNow(
                $user,
                $notification
            );
        } else {
            if ($sentAt > Carbon::now()->subMinute()) {
            }
        }
    }

    /**
     * Send Notif now / immediately
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @param \App\Models\User $user
     * @return void
     */
    public static function sendNow(Notification $notification, User $user)
    {
        return self::send($notification, $user);
    }

    /**
     * Notify user after job queue done
     *
     * @param \Illuminate\Foundation\Bus\PendingDispatch $job
     * @param \Bagoesz21\LaravelNotification\Notifications\SystemNotif $systemNotif
     * @param \App\Models\User|null $user. If null, logged user used
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public static function notifyAfterJob($job, SystemNotif $systemNotif, ?User $user = null)
    {
        if (is_null($user)) {
            $user = Auth::user();
        }

        $systemNotifJob = new SystemNotifJob(
            $systemNotif,
            $user
        );

        return $job->chain([
            $systemNotifJob
        ]);
    }
}
