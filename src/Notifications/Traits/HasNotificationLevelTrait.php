<?php

namespace Bagoesz21\LaravelNotification\Notifications\Traits;

use Bagoesz21\LaravelNotification\Enums\NotificationLevel;

trait HasNotificationLevelTrait
{
    public $level = NotificationLevel::INFO;

    /**
     * @param  \Bagoesz21\LaravelNotification\Enums\NotificationLevel|int  $level
     * @return \Bagoesz21\LaravelNotification\Enums\NotificationLevel
     */
    protected function convertLevel($level)
    {
        if (! ($level instanceof NotificationLevel)) {
            $level = NotificationLevel::from($level);
        }

        return $level;
    }

    /**
     * Set level notification
     * Level value / enum
     *
     * @param  int|\Bagoesz21\LaravelNotification\Enums\NotificationLevel  $level
     * @return self
     */
    public function setLevel($level)
    {
        $this->level = $this->convertLevel($level);

        return $this;
    }

    /**
     * Get level notification
     *
     * @return \Bagoesz21\LaravelNotification\Enums\NotificationLevel
     */
    public function getLevel()
    {
        return $this->level;
    }

    public function infoNotif()
    {
        $this->setLevel(NotificationLevel::INFO);

        return $this;
    }

    public function successNotif()
    {
        $this->setLevel(NotificationLevel::SUCCESS);

        return $this;
    }

    public function warningNotif()
    {
        $this->setLevel(NotificationLevel::WARNING);

        return $this;
    }

    public function errorNotif()
    {
        $this->setLevel(NotificationLevel::ERROR);

        return $this;
    }

    /**
     * @param  \Bagoesz21\LaravelNotification\Enums\NotificationLevel|int  $level
     * @return bool
     */
    public function isLevelNotif($level)
    {
        $level = $this->convertLevel($level);

        return $this->level->is($level);
    }

    /**
     * @return bool
     */
    public function isInfoNotif()
    {
        return $this->isLevelNotif(NotificationLevel::INFO);
    }

    /**
     * @return bool
     */
    public function isSuccessNotif()
    {
        return $this->isLevelNotif(NotificationLevel::SUCCESS);
    }

    /**
     * @return bool
     */
    public function isWarningNotif()
    {
        return $this->isLevelNotif(NotificationLevel::WARNING);
    }

    /**
     * @return bool
     */
    public function isErrorNotif()
    {
        return $this->isLevelNotif(NotificationLevel::ERROR);
    }
}
