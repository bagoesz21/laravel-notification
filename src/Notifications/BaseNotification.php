<?php

namespace Bagoesz21\LaravelNotification\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;

use Bagoesz21\LaravelNotification\Enums\NotificationLevel;
use Bagoesz21\LaravelNotification\Helpers\NotifHelper;
use Bagoesz21\LaravelNotification\Notifications\Traits\HasUTMTrait;

class BaseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    use Traits\HasChannels;
    use Traits\HasActionNotificationTrait;
    use Traits\HasNotificationLevelTrait;
    use Traits\UseProseMirrorAsMessage;
    use Traits\HasTagAndMetaData;
    use Traits\HasUTMTrait;

    use Formatters\MailChannel;
    use Formatters\BroadcastChannel;
    use Formatters\OneSignalChannel;

    protected $debug = false; //for debugging purpose
    public $enableLog = false;

    public $title = null;
    public $message = null;

    public $image = null;
    public array $data;

    protected $unsubscribeInfo = false;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Static create notification
     *
     * @param string|null $title
     * @param string|null $message
     * @param array|null $data
     * @param array|null $notifChannels
     * @return static
     */
    public static function create($title = null, $message = null, $data = [], $notifChannels = [])
    {
        $class = get_called_class();
        return (new $class())
        ->initNotif($title, $message, $data, $notifChannels);
    }

    public static function config() : array
    {
        return [];
    }

    /**
     * Get config on this notification
     *
     * @param string|null $key
     * @return array|string|int|null
     */
    public static function getConfig($key = null)
    {
        return Arr::get(get_called_class()::config(), $key);
    }

    /**
     * Set title notification
     *
     * @param string|null $title
     * @return self
    */
    public function setTitle($title)
    {
        if(is_null($title))return $this;
        $this->title = $title;
        return $this;
    }

    /**
     * Get title notification
     *
     * @return string
    */
    public function getTitle()
    {
        return !empty($this->title) ? $this->title : $this->getReadableNotifType();
    }

    /**
     * Set message notification
     *
     * @param string|null $message
     * @return self
    */
    public function setMessage($message)
    {
        if(is_null($message))return $this;
        $this->message = $message;
        return $this;
    }

    /**
     * Set message from view blade
     *
     * @param \Illuminate\View\View $view
     * @return self
     */
    public function setMessageFromView($view)
    {
        $html = '';
        if($view instanceof View){
            $html = $view->render();
        }

        if(empty($html))return $this;
        $this->setMessage($html);
        return $this;
    }

    /**
     * Set message from view blade
     *
     * @return \Illuminate\View\View
     */
    protected function viewMessage()
    {

    }

    /**
     * Get message notification
     *
     * @return string
    */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get message notif as plain text
     *
     * @return string
     */
    public function getMessageAsPlainText()
    {
        return $this->getMessage();
    }

    /**
     * Get message notif as HTML
     *
     * @return string
     */
    public function getMessageAsHTML()
    {
        return "<p>" . $this->getMessage() . "</p>";
    }

    /**
     * Set data notification
     *
     * @param array $data
     * @return self
    */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Set image notification
     *
     * @param string $image. path image
     * @return self
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Get image notification
     *
     * @return self
     */
    public function getImageUrl()
    {
        return url($this->image);
    }

    /**
     * Init notification
     *
     * @param string|null $title
     * @param string|null $message
     * @param array|null $data
     * @param array|null $notifChannels
     * @return self
    */
    public function initNotif($title = null, $message = null, $data = [], $notifChannels = [])
    {
        $this->setDebug(config('app.debug'));
        $this->onConnection(config('notification.connection'));

        if(config('notification.after_commit')){
            $this->afterCommit();
        }

        $this->locale(config('notification.locale'));

        $this->setTitle($title)->setMessage($message)->setData($data)->setChannelsWithMandatory($notifChannels)->setLevel(NotificationLevel::INFO)->initChannel();

        // $this->afterInit();
        $this->setMessageToProseMirror();
        return $this;
    }

    /**
     * @return mixed
     */
    public function afterInit()
    {
        foreach (trait_uses_recursive(BaseNotification::class) as $key => $value) {
            if (method_exists($this, $method = 'afterInit'.Str::studly($value))) {
                return $this->{$method};
            }
        }

        return $this;
    }

    /**
     * Build data notification
     *
     * @return self
     */
    public function build()
    {
        $this->setMessageFromView($this->viewMessage());
        return $this;
    }

    /**
     * Get readable type notification
     *
     * @return string
    */
    public function getReadableNotifType()
    {
        $currentClass = (new \ReflectionClass(get_called_class()))->getShortName();

        return Arr::first(NotifHelper::getNotificationClass()::listNotificationType(), function ($value, $key) use ($currentClass) {
            return $value['class'] === $currentClass;
        });
    }

    /**
     * Get data on notification array
     *
     * @param string $key
     * @return mixed
    */
    public function getData($key)
    {
        if (empty($this->data)) {
            return;
        }

        return Arr::get($this->data, $key);
    }

    /**
     * Set a specific queue that should be used for each notification channel supported by the notification
     *
     * @return array
     */
    public function viaQueues()
    {
        return Arr::pluck($this->listChannels(), 'queue_name', 'value');
    }

    /**
     * Default notif channel broadcast / websocket.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->getTitle(),
            'id' => $this->getData('id'),
            'image' => $this->getData('image'),
            'database_notif' => $this->isNotifVia('database'),
            'actions' => $this->getActions(),
        ];
    }

    /**
     * Determine if the notification should be sent.
     *
     * @param  mixed  $notifiable
     * @param  string  $channel
     * @return bool
     */
    public function shouldSend($notifiable, $channel)
    {
        if($this->debug)return true;
        return true;
    }

    /**
     * Show unsubscribe information in mail
     *
     * @param boolean $toggle
     * @return self
     */
    public function unsubscribeInfo($toggle = true)
    {
        $this->unsubscribeInfo = $toggle;
        return $this;
    }

    /**
     * Determine the notification's delivery delay.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function withDelay($notifiable)
    {
        $date = now();
        return array_filter($this->listChannels(), function($channel) use ($date) {
            if(!array_key_exists('delay', $channel))return [];

            return [
                Arr::get($channel, 'value') => $date->addMinutes(Arr::get($channel, 'delay', 0))
            ];
        });
    }

    /**
     * @param boolean $toggle
     * @return self
     */
    public function setDebug($toggle = true)
    {
        if(config('app.env') === 'production')return $this;

        $this->debug = $toggle;
        return $this;
    }
}
