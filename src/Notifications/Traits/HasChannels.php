<?php

namespace Bagoesz21\LaravelNotification\Notifications\Traits;

use Illuminate\Support\Arr;

trait HasChannels
{
    public $notifChannels = [];

    protected $selectedChannel = null;

    /**
     * List all supported channels.
     *
     * @return array
     */
    public function listChannels()
    {
        return $this->notifConfig->get('channels');
    }

    /**
     * List always allowed channels / mandatory channels.
     *
     * @return array
     */
    public function listMandatoryChannels()
    {
        return $this->notifConfig->get('mandatory_channels');
    }

    /**
     * List alias channels (key for naming database, value for naming class notification laravel)
     *
     * @return array
     */
    public function listAliasChannels()
    {
        return Arr::pluck($this->listChannels(), 'class', 'value');
    }

    /**
     * List default allowed channels / default channels.
     *
     * @return array
     */
    public function listDefaultChannels()
    {
        return $this->notifConfig->get('default_channels');
    }

    /**
     * Set selected channel
     *
     * @param  string  $channelKey
     * @return self
     */
    public function setSelectedChannel($channelKey)
    {
        $this->selectedChannel = $channelKey;

        return $this;
    }

    /**
     * Get selected channel
     *
     * @param  string  $channelKey
     * @return array
     */
    public function getChannel($channelKey = null)
    {
        if (is_null($channelKey)) {
            $channelKey = $this->selectedChannel;
        }

        return Arr::get($this->listChannels(), $channelKey);
    }

    /**
     * Set default channels
     *
     * @param  array|null  $defaultChannels
     * @return array
     */
    public function setDefaultChannels($defaultChannels = [])
    {
        if (empty($defaultChannels)) {
            return $this->listDefaultChannels();
        }

        return $defaultChannels;
    }

    /**
     * Set channels notification
     *
     * @param  array  $channels
     * @return self
     */
    public function setChannels($channels)
    {
        $this->notifChannels = $this->getAliasChannel($channels);

        return $this;
    }

    /**
     * Set channels with mandatory channels
     *
     * @param  array|null  $notifChannels
     * @return self
     */
    public function setChannelsWithMandatory($notifChannels = [])
    {
        if (empty($notifChannels)) {
            $notifChannels = $this->mergeWithMandatoryChannel($this->listDefaultChannels());
        }

        $this->setChannels($notifChannels);

        return $this;
    }

    /**
     * Init each channel before send notif
     *
     * @return self
     */
    public function initChannel()
    {
        $channels = $this->listChannels();
        foreach ($channels as $key => $channel) {
            $channelValue = str_replace(' ', '', ucwords($channel['value']));
            $methodName = 'init'.$channelValue;
            if (method_exists($this, $methodName)) {
                $this->{$methodName}();
            }
        }

        return $this;
    }

    /**
     * Merge channels with mandatory channels.
     *
     * @param  array|null  $selectedChannels
     * @return array $allowedChannels
     */
    public function mergeWithMandatoryChannel($selectedChannels = [])
    {
        if (empty($selectedChannels)) {
            $this->listMandatoryChannels();
        }

        return $this->getAliasChannel(array_merge($selectedChannels, $this->listMandatoryChannels()));
    }

    /**
     * Convert channel in database into class channel notification
     *
     * @return array
     */
    public function getAliasChannel($channels = [])
    {
        if (empty($channels)) {
            return [];
        }

        $result = [];
        foreach ($channels as $key => $channel) {
            if (! Arr::has($this->listAliasChannels(), $channel)) {
                continue;
            }

            $result[] = Arr::get($this->listAliasChannels(), $channel);
        }

        return $result;
    }

    /**
     * Is notif via channel ?
     *
     * @param  string  $channelName
     * @return bool
     */
    public function isNotifVia($channelName)
    {
        if (empty($this->notifChannels)) {
            return false;
        }

        return in_array($channelName, $this->notifChannels);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->notifChannels;
    }
}
