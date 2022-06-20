<?php

namespace Bagoesz21\LaravelNotification\Helpers;

use Illuminate\Support\Arr;

class NotifConfig
{
    protected $defaultChannel = [];
    protected $queueName = 'default';
    protected $queueConnection = 'redis';
    protected $afterCommit = true;
    protected $locale = null;

    protected $defaultUTM = [
        [
            'key' => 'source',
            'value' => 'notification'
        ],
    ];

    protected $channels = [];
    protected $other = [];

    private $localize = true;

    /**
     * @return self
     */
    public static function make()
    {
        return (new self());
    }

    public function __construct()
    {
        $this->setDefaultChannel();
    }

    public function queueName($val)
    {
        $this->queueName = $val;
        return $this;
    }

    public function queueConnection($val)
    {
        $this->queueConnection = $val;
        return $this;
    }

    public function afterCommit($val)
    {
        $this->afterCommit = $val;
        return $this;
    }

    public function locale($val)
    {
        $this->locale = $val;
        return $this;
    }

    public function localize($val)
    {
        $this->localize = $val;
        return $this;
    }

    public function setDefaultChannel()
    {
        $this->defaultChannel = [
            'enabled' => false,
            'name' => null,
            'value' => null,
            'default' => false,
            'class' => null,
            'mandatory' => false,
            'description' => '',
            'queue_name' => $this->queueName,
            'queue_connection' => $this->queueConnection,
            'icon' => 'mdi-bell',
            'utm' => $this->defaultUTM
        ];
        return $this;
    }

    public function channels($channels)
    {
        $channels = array_map(function($channel) {
            return array_merge($this->defaultChannel, $channel);
        }, $channels);
        $this->channels = $channels;
        return $this;
    }

    public function mapChannel($config)
    {
        $channels = array_map(function($channel){
            return array_merge($this->defaultChannel, $channel);
        }, $this->channels);

        $enabledChannels = array_filter($channels, function($channel) {
            return $channel['enabled'];
        });

        return array_merge($config, [
            'all' => $channels,
            'channels' => $enabledChannels,
            'mandatory_channels' => Arr::pluck(Arr::where($enabledChannels, function ($channel){
                return ($channel['mandatory'] === true);
            }), 'value'),
            'default_channels' => Arr::pluck(Arr::where($enabledChannels, function ($channel){
                return ($channel['default'] === true);
            }), 'value'),
        ]);
    }

    public function other($val)
    {
        $this->other = $val;
        return $this;
    }

    public function buildMinimal()
    {
        return array_merge([
            'connection' => $this->queueConnection,
            'queue_name' => $this->queueName,
            'after_commit' => $this->afterCommit,
            'locale' => $this->locale,
            'channels' => $this->translateChannels($this->channels),

            'utm' => $this->defaultUTM,
        ], $this->other);
    }

    public function build()
    {
        return array_merge($this->mapChannel($this->buildMinimal()));
    }

    public function translateChannels($channels)
    {
        if(!$this->localize)return $channels;
        $packageName = "laravel-notification";
        $locale = "$packageName::notification.channels.";

        return array_map(function($channel, $key) use($packageName, $locale) {
            return array_merge($channel, [
                'name' => trans("$locale$key.name"),
                'description' => trans("$locale$key.description"),
            ]);
        }, $channels, array_keys($channels));
    }

    public function config($config)
    {
        $this->queueName(Arr::get($config, 'queue_name'))
        ->queueConnection(Arr::get($config, 'connection'))
        ->afterCommit(Arr::get($config, 'after_commit'))
        ->locale(Arr::get($config, 'locale'))
        ->channels(Arr::get($config, 'channels', []))
        ->other(Arr::except($config, [
            'queue_name', 'connection', 'after_commit', 'locale', 'channels', 'utm', 'all', 'mandatory_channels', 'default_channels', 'notifications'
        ]));
        return $this;
    }

    public function translatedConfig($config)
    {
        return $this->localize(true)->config($config)->build();
    }
}
