<?php

namespace Bagoesz21\LaravelNotification\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

use App\Models\User;

class SystemNotifJob implements
    ShouldQueue
    //, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notification;
    protected $user;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @param \App\Models\User $user
     * @return void
     */
    public function __construct(
        Notification $notification,
        User $user
    ){
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
