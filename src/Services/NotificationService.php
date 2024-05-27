<?php

namespace Bagoesz21\LaravelNotification\Services;

use Bagoesz21\LaravelNotification\Events\NotificationRead;
use Bagoesz21\LaravelNotification\Events\NotificationReadAll;
use Bagoesz21\LaravelNotification\Helpers\NotifHelper;
use Illuminate\Notifications\Notification as LaravelNotification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /** @var \Bagoesz21\LaravelNotification\Models\Notification */
    public $model;

    public function __construct()
    {
        $this->model = NotifHelper::getNotifModelClass();
    }

    /**
     * Static make notification
     *
     * @return static
     */
    public static function make()
    {
        $class = get_called_class();

        return new $class();
    }

    /**
     * @param  \Illuminate\Support\Collection  $input
     * @return bool
     */
    public function markAsRead($input)
    {
        $userID = Auth::id();
        $notifID = $input->get('id');
        $notifID = is_array($notifID) ? $notifID : [$notifID];

        $unread = $this->model::where('notifiable_id', $userID)
            ->whereNull('read_at')
            ->whereIn('id', $notifID)
            ->count();

        $isUnread = ($unread > 0) ? true : false;

        $msg = '';
        if ($isUnread) {
            $this->model::where('notifiable_id', $userID)
                ->whereNull('read_at')
                ->whereIn('id', $notifID)
                ->update(['read_at' => now()]);

            $msg = 'Notif ditandai sudah dibaca';
            event(new NotificationRead(Auth::user(), $notifID));
        }

        return true;
    }

    /**
     * @param  \Illuminate\Support\Collection  $input
     * @return bool
     */
    public function markAllRead($input)
    {
        $userID = Auth::id();

        $unread = $this->model::where('notifiable_id', $userID)
            ->whereNull('read_at')
            ->count();

        $isUnread = ($unread > 0) ? true : false;

        $msg = '';
        if ($isUnread) {
            $this->model::where('notifiable_id', $userID)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $msg = 'Semua notif ditandai sudah dibaca';
            event(new NotificationReadAll(Auth::user()));
        }

        return true;
    }

    /**
     * Batch send notif to users
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $users
     * @param  array  $deliveryConfig
     * @param  array  $batchConfig
     * @param  array  $otherConfig
     * @return mixed
     */
    public function batchSendNotif($users, LaravelNotification $laravelNotification, $deliveryConfig = [], $batchConfig
    = [], $otherConfig = [])
    {
        $result = null;
        try {
            $batch = new BatchSendNotifService;
            $batch->setDeliveryConfig($deliveryConfig)
                ->setBatchConfig($batchConfig)
                ->setNotification($laravelNotification)
                ->setUsers($users)
                ->setNotifConfig($otherConfig);

            $result = $batch->send();
        } catch (\Throwable $th) {
            \Log::error($th);
        }

        return $result;
    }

    /**
     * Merge notif (same data / duplicate & unread) to avoid spam notif.
     * If notif is present (duplicate & unread), count notif then merge into one new notif
     *
     * @param  int|string  $notifiableId
     * @param  string  $notifType
     * @param  int|string  $uniqueID
     * @param  string|null  $prefixResult
     * @return string
     **/
    public function mergeUnreadNotifToAvoidSpam($notifiableId, $notifType, $uniqueID, $prefixResult = '')
    {
        if (empty($notifiableId) && empty($uniqueID)) {
            return '';
        }

        $unreadNotifInSameData = $this->model::where('notifiable_id', $notifiableId)
            ->where('data->id', $uniqueID)
            ->where('data->notif_type', $notifType);
        $countUnreadNotifInSameData = $unreadNotifInSameData->count();

        $result = '';
        if ($countUnreadNotifInSameData > 0) {
            $formatNumber = $countUnreadNotifInSameData + 1;
            $result = ' '.$formatNumber;
            $result .= ! empty($prefixResult) ? ' '.$prefixResult : '';
            $unreadNotifInSameData->delete();
        }

        // \Log::info('mergeNotifToAvoidSpam', [
        //     'countUnreadNotifInSameData' => $countUnreadNotifInSameData,
        //     'result' => $result,
        // ]);
        return $result;
    }

    /**
     * @param  \Illuminate\Support\Collection  $input
     * @return bool
     */
    public function markAsUnRead($input)
    {
        $userID = Auth::id();
        $notifID = $input->get('id');
        $notifID = is_array($notifID) ? $notifID : [$notifID];

        $readed = $this->model::where('notifiable_id', $userID)
            ->whereNotNull('read_at')
            ->whereIn('id', $notifID)
            ->count();

        $isReaded = ($readed > 0) ? true : false;

        if ($isReaded) {
            $this->model::where('notifiable_id', $userID)
                ->whereNotNull('read_at')
                ->whereIn('id', $notifID)
                ->update(['read_at' => null]);
        }

        return true;
    }

    /**
     * @param  \Illuminate\Support\Collection  $input
     * @return bool
     */
    public function delete($input)
    {
        $userID = Auth::id();
        $notifID = $input->get('id');
        $notifID = is_array($notifID) ? $notifID : [$notifID];

        $readed = $this->model::where('notifiable_id', $userID)
            ->whereIn('id', $notifID)
            ->delete();

        return true;
    }
}
