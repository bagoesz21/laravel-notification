<?php

namespace Bagoesz21\LaravelNotification\Services;

use App\Models\User;
use Bagoesz21\LaravelNotification\Config\NotifConfig;
use Bagoesz21\LaravelNotification\Enums\DeliveryTimeStatus;
use Bagoesz21\LaravelNotification\Helpers\Helper;
use Bagoesz21\LaravelNotification\Jobs\BatchNotifJob;
use Carbon\Carbon;
use Illuminate\Bus\Batch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notification as LaravelNotification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class BatchSendNotifService
{
    protected $notifConfig;

    protected $deliveryConfig;

    protected $batchConfig = [];

    /** @var \Illuminate\Notifications\Notification */
    protected $laravelNotification;

    /** @var \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder */
    protected $builderUsers;

    protected $response;

    protected $enableLog = true;

    /** @var \Illuminate\Bus\Batch */
    protected $batch;

    protected $chunkLimit = 1000;

    /**
     * Handle multiple constructor
     */
    public function __construct()
    {
        $this->enableLog = App::environment('production') ? true : false;

        $this->notifConfig = NotifConfig::make();
    }

    /**
     * Set other notif config
     *
     * @param  array  $config
     * @return self
     */
    public function setNotifConfig($config)
    {
        $this->notifConfig = $config;

        return $this;
    }

    /**
     * Set delivery notif config
     *
     * @param  array  $config
     *                         $config = [  //Notification delivery time
     *                         'status' => '1' //DeliveryTimeStatus delivery immediately / schedule
     *                         'delivery_at' => null //DateTime delivery schedule
     *                         ]
     * @return self
     */
    public function setDeliveryConfig($config)
    {
        $this->deliveryConfig = array_merge($this->getDefaultDeliveryConfig(), $config);

        return $this;
    }

    /**
     * Get default delivery notif config
     *
     * @return array
     */
    public function getDefaultDeliveryConfig()
    {
        return [
            'status' => DeliveryTimeStatus::IMMEDIATELY,
            'delivery_at' => Carbon::now(),
        ];
    }

    /**
     * Set batch config
     *
     * @param  array  $batchConfig
     *                              $batchConfig = [
     *                              'name' => 'Send Notif User' //Batch Job Name
     *                              'connection' => 'redis' //Batch Job Connection Name, default redis
     *                              'queue' => 'default' //Batch Job Queue Name
     *                              'allow_failures' => true //Batch Job allow on failure
     *                              ]
     * @return self
     */
    public function setBatchConfig($batchConfig)
    {
        $this->batchConfig = array_merge($this->getDefaultBatchConfig(), $batchConfig);

        return $this;
    }

    /**
     * Get default batch config
     *
     * @return array
     */
    public function getDefaultBatchConfig()
    {
        return [
            'name' => 'Send Notif To User',
            'connection' => Arr::get($this->notifConfig, 'connection'),
            'queue' => Arr::get($this->notifConfig, 'queue_name'),
            'allow_failures' => true,
        ];
    }

    /**
     * Set laravel notification
     *
     * @return self
     */
    public function setNotification(LaravelNotification $notification)
    {
        $this->isInstanceOfNotif($notification);

        $this->laravelNotification = $notification;

        return $this;
    }

    /**
     * @return void
     *
     * @throws \Exception
     */
    protected function isInstanceOfNotif(LaravelNotification $notification)
    {
        if (! ($notification instanceof LaravelNotification)) {
            throw new \Exception('Notification not instance of laravel notification.');
        }
    }

    /**
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $val
     * @return void
     *
     * @throws \Exception
     */
    public function isInstanceOfQuery($val)
    {
        if (! ($val instanceof \Illuminate\Database\Query\Builder)) {
            throw new \Exception('Query user not instance of query builder.');
        }

        if (! ($val instanceof \Illuminate\Database\Eloquent\Builder)) {
            throw new \Exception('Query user not instance of eloquent builder.');
        }
    }

    /**
     * Notif to user
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $users
     * @return self
     *
     * @throws \Exception
     */
    public function setUsers($users)
    {
        $this->isInstanceOfQuery($users);

        $this->builderUsers = $users;

        return $this;
    }

    /**
     * Set chunk limit for processing notif user into job queue
     *
     * @param  int  $limit
     * @return self
     */
    public function setChunkLimit($limit)
    {
        $this->chunkLimit = $limit;

        return $this;
    }

    /**
     * Send batch notif to user
     *
     * @return void
     *
     * @throws \Exception
     */
    public function send()
    {
        $this->isInstanceOfQuery($this->builderUsers);

        $this->isInstanceOfNotif($this->laravelNotification);

        try {
            $users = $this->builderUsers->select(['users.id', 'email', 'name']);
            //->get();
            //dd($users->toSql());
            // dd($users->get()->toArray());

            $this->processBatch($users);

            $deliveryTimeStatus = $this->getDeliveryTimeStatus();
            $deliveryAt = $this->getDeliveryAt();

            if ($deliveryTimeStatus == DeliveryTimeStatus::SCHEDULE) {
                $message = 'Notifikasi dijadwal kirim pada '.$deliveryAt->translatedFormat('l, d F Y H:i');
            } else {
                $message = 'Notifikasi sedang proses dikirim';
            }
        } catch (\Throwable $th) {
            Helper::logError($th);
        }
    }

    /**
     * Process batch send notif to user
     *
     * @return \Illuminate\Bus\Batch
     **/
    protected function processBatch(Builder $users)
    {
        $logName = 'Batch '.Arr::get($this->batchConfig, 'name');
        $now = Carbon::now()->format('d M Y H:i:s');
        $enableLog = $this->enableLog;

        $this->batch = Bus::batch([])
            ->then(function (Batch $batch) {

                // if($enableLog){
                //     Log::info($logName . " Then", ['batch' => $batch]);
                // }
            })->catch(function (Batch $batch, Throwable $th) use ($logName) {
                Log::error($logName.' Error', ['batch' => $batch, 'th' => $th]);
            })->finally(function (Batch $batch) {
                // if($enableLog){
                //     Log::info($logName . " Finally", ['batch' => $batch]);
                // }
            })
            ->name(Arr::get($this->batchConfig, 'name'))
            ->onConnection(Arr::get($this->batchConfig, 'connection'))
            ->onQueue(Arr::get($this->batchConfig, 'queue'))
            ->allowFailures((bool) Arr::get($this->batchConfig, 'allow_failures', true))
            ->dispatch();

        $deliveryTimeStatus = $this->getDeliveryTimeStatus();
        $deliveryAt = $this->getDeliveryAt();

        if ($enableLog) {
            Log::info('Batch '.$this->batch->id.' Logs', [
                'batch' => $this->batch,
                'date' => $now,
                'deliveryConfig' => [
                    'deliveryTimeStatus' => $deliveryTimeStatus,
                    'deliveryAt' => $deliveryAt,
                ],
                'batchConfig' => $this->batchConfig,
                'query' => $users->toRawSql(),
            ]);
        }

        $users->cursor()
            ->map(function (User $user) use ($deliveryTimeStatus, $deliveryAt) {
                return new BatchNotifJob($this->laravelNotification, $user,
                    ($deliveryTimeStatus->is(DeliveryTimeStatus::SCHEDULE) ? $deliveryAt : null)
                );
            })
            ->filter()
            ->chunk($this->chunkLimit)
            ->each(function (\Illuminate\Support\LazyCollection $jobs) {
                $this->batch->add(
                    $jobs
                );
            });

        return $this->batch;
    }

    /**
     * Get batch instance
     *
     * @return \Illuminate\Bus\Batch
     **/
    public function getBatch()
    {
        return $this->batch;
    }

    /**
     * Get delivery time status
     *
     * @return \Bagoesz21\LaravelNotification\Enums\DeliveryTimeStatus
     **/
    public function getDeliveryTimeStatus()
    {
        $inputDeliveryTime = (int) Arr::get($this->deliveryConfig, 'status',
            DeliveryTimeStatus::getDefaultValue());

        return DeliveryTimeStatus::from($inputDeliveryTime);
    }

    /**
     * Get delivery at (date time)
     *
     * @return \Carbon\Carbon
     **/
    public function getDeliveryAt()
    {
        return Carbon::parse(Arr::get($this->deliveryConfig, 'delivery_at'))->setTimezone(config('app.timezone'));
    }

    /**
     * Enable log batch process
     *
     * @param  bool  $status
     * @return self
     */
    public function enableLog($status)
    {
        $this->enableLog = $status;

        return $this;
    }

    /**
     * Log Batch Process
     *
     * @return bool
     **/
    public function logBatch($logName, $stackTraces)
    {
        if (! $this->enableLog) {
            return false;
        }

        Log::info($logName, $stackTraces);

        return true;
    }
}
