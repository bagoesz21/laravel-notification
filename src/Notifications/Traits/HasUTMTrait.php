<?php

namespace Bagoesz21\LaravelNotification\Notifications\Traits;

use Illuminate\Support\Arr;

trait HasUTMTrait
{
    protected $utm = [];

    protected $enableUTM = true;

    /**
     * Enable UTM into notification
     *
     * @param  bool  $toggle
     * @return self
     */
    public function enableUTM($toggle = true)
    {
        $this->enableUTM = $toggle;

        return $this;
    }

    /**
     * Set Urchin Tracking Module (UTM) without prefix
     * example :
     * $utm = [
     *      'key' => 'medium',
     *      'value' => 'email'
     * ]
     *
     * @return self
     */
    public function setUTM(array $utm)
    {
        $this->utm = $utm;

        return $this;
    }

    /**
     * Get UTM
     *
     * @return array
     */
    public function getUTM()
    {
        $utmResult = $this->getDefaultUTM($this->selectedChannel);

        // if(!empty($this->utm)){
        //     $utmResult = array_merge_recursive($utmResult, $this->utm);
        //     $utmResult = $this->cleanUTM($utmResult);
        // }
        $this->utm = $utmResult;

        return $this->buildUTM($utmResult);
    }

    /**
     * @param  array  $datas
     * @return array
     */
    private function cleanUTM($datas)
    {
        $datas = collect($datas);
        $result = collect($datas);
        $duplicates = $datas->duplicates('key')->values()->toArray();

        if (! empty($duplicates)) {
            $result = collect($datas->whereNotIn('key', $duplicates)->toArray());

            foreach ($duplicates as $key => $duplicateKey) {
                $last = $datas->last(function ($value, $key) use ($duplicateKey) {
                    return $value['key'] === $duplicateKey;
                });
                $result->push($last);
            }
        }

        return $result->toArray();
    }

    private function buildUTM($utm)
    {
        return array_map(function ($utm) {
            return array_merge($utm, [
                'key' => 'utm_'.$utm['key'],
            ]);
        }, $utm);
    }

    /**
     * Get default UTM config for notification
     *
     * @return array
     */
    public function getDefaultUTM($channelKey)
    {
        $channels = $this->listChannels();
        $channel = Arr::get($channels, $channelKey);
        if (empty($channel)) {
            return [];
        }

        return Arr::get($channel, 'utm', []);
    }

    /**
     * Get UTM config for notification as key value
     *
     * @return array
     */
    public function getUTMAsKeyValue()
    {
        $utm = $this->getUTM();
        if (empty($utm)) {
            return [];
        }

        return Arr::pluck($utm, 'value', 'key');
    }

    /**
     * Get UTM config for notification as query string
     *
     * @return string
     */
    public function getUTMAsQuery()
    {
        $utm = $this->getUTMAsKeyValue();
        $utm = Arr::query($utm);

        return $utm;
    }
}
