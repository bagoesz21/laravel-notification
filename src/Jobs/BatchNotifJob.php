<?php

namespace Bagoesz21\LaravelNotification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Batchable;
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Carbon\Carbon;

use App\Models\User;
use App\Helpers\NotifHelper;

class BatchNotifJob implements
    ShouldQueue
    //, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use Batchable;

    protected $notification;
    protected $user;
    protected $deliveryAt;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @param \App\Models\User $user
     * @param \Carbon\Carbon|null $deliveryAt
     * @return void
     */
    public function __construct(
        Notification $notification,
        User $user,
        Carbon $deliveryAt = null
    ){
        $this->notification = $notification;
        $this->user = $user;
        $this->deliveryAt = $deliveryAt;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->batch()->cancelled()) {
            //\Log::info("BatchNotifJob Canceled", ["batch" => $this->batch()]);

            return;
        }

        NotifHelper::send($this->notification, $this->user, $this->deliveryAt);
    }
}
