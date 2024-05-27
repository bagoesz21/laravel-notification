<?php

namespace Bagoesz21\LaravelNotification\Jobs;

use App\Models\User;
use Bagoesz21\LaravelNotification\Helpers\NotifHelper;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BatchNotifJob implements ShouldQueue
    //, ShouldBeUnique
{
    use Batchable;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification;

    protected $user;

    protected $deliveryAt;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Notification $notification,
        User $user,
        ?Carbon $deliveryAt = null
    ) {
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
