<?php

namespace Bagoesz21\LaravelNotification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Bagoesz21\LaravelNotification\Helpers\NotifHelper;
use Carbon\Carbon;

class DeleteOldReadNotifJob implements
    ShouldQueue
    //, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \Bagoesz21\LaravelNotification\Models\Notification */
    public $notification;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->notification = NotifHelper::getNotifModelClass();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $dateEnd = Carbon::now();
        $dateStart = Carbon::now()->sub(3, "month");

        $this->notification->where('read_at', '>=', $dateStart)
            ->where('read_at', '<=', $dateEnd)->delete();
    }
}
