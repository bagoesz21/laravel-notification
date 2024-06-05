<?php

namespace Bagoesz21\LaravelNotification\Helpers;

use App\Models\User;
use Bagoesz21\LaravelNotification\Jobs\SystemNotifJob;
use Bagoesz21\LaravelNotification\LaravelNotification;
use Bagoesz21\LaravelNotification\Notifications\SystemNotif;
use Carbon\Carbon;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class NotifHelper
{
    public static function getNotifModelClass()
    {
        return LaravelNotification::make()->notifModelClass();
    }

    public static function getNotifLogModelClass()
    {
        return LaravelNotification::make()->notifLogModelClass();
    }

    public static function getNotifTemplateModelClass()
    {
        return LaravelNotification::make()->notifTemplateModelClass();
    }

    public static function getNotifClass()
    {
        return LaravelNotification::make()->notifClass();
    }

    public static function messageParser()
    {
        return LaravelNotification::make()->messageParser();
    }

    public static function messageParserToHtml($message = null)
    {
        return self::messageParser()->setMessage($message)->toHtml();
    }

    /**
     * Send Notif immediately / schedule
     *
     * @return void
     */
    public static function send(Notification $notification, User $user, ?Carbon $sentAt = null)
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
     * @return void
     */
    public static function sendNow(Notification $notification, User $user)
    {
        self::send($notification, $user);
    }

    /**
     * Notify user after job queue done
     *
     * @param  \Illuminate\Foundation\Bus\PendingDispatch  $job
     * @param  \App\Models\User|null  $user.  If null, logged user used
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
            $systemNotifJob,
        ]);
    }
}
