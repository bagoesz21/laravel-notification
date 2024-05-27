<?php

namespace Bagoesz21\LaravelNotification\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class SystemNotifJob implements ShouldQueue
    //, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification;

    protected $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Notification $notification,
        User $user
    ) {
        $this->notification = $notification;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        NotificationFacade::sendNow(
            $this->user,
            $this->notification
        );
    }
}
