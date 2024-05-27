<?php

namespace Bagoesz21\LaravelNotification\Console\Commands;

use App\Models\User;
use Bagoesz21\LaravelNotification\Helpers\NotifHelper;
use Bagoesz21\LaravelNotification\Notifications\GeneralNotif;
use Carbon\Carbon;
use Illuminate\Console\Command;

class NotifSendCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:send
                        {--t|title= : Title notif}
                        {--m|message= : Message notif}
                        {--c|channels=* : Channels notif}
                        {--u|user=* : Target user id}
                        {--s|sent_at=* : Sent at (date time). Scheduled}
                        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send immediate or scheduled notifications';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $title = $this->option('title');
        $message = $this->option('message');
        $channels = $this->option('channels');

        $users = $this->option('user');

        $notif = GeneralNotif::create($title, $message, [], $channels);

        $sent_at = $this->option('sent_at');

        if (! empty($sent_at)) {
            $sent_at = Carbon::parse($sent_at);
        }

        foreach ($users as $key => $user) {
            $user = User::find($user);
            if (empty($user)) {
                continue;
            }

            NotifHelper::send($notif, $user, $sent_at);

            $userDesc = optional($user)->name;
            $this->info("Sending notif to: {$userDesc}!");
        }
    }
}
