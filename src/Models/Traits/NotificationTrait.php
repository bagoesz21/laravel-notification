<?php

namespace Bagoesz21\LaravelNotification\Models\Traits;

use Illuminate\Support\Arr;

use App\Services\ProseMirror\Renderer;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

trait NotificationTrait
{
    public static function listNotificationType()
    {
        $result = [
            [
                'name' => 'Notifikasi Umum',
                'key' => 'GeneralNotif',
                'class' => \Bagoesz21\LaravelNotification\Notifications\GeneralNotif::class,
            ],
            [
                'name' => 'Notifikasi Sistem',
                'key' => 'SystemNotif',
                'class' => \Bagoesz21\LaravelNotification\Notifications\SystemNotif::class,
            ],
        ];
        return $result;
    }

    /**
     * Get full class notification by type
     *
     * @param array|string $types
     * @return array
     */
    public static function getFullClassNotificationType($types)
    {
        if (empty($types)) {
            return [];
        }
        $types = is_array($types) ? $types : [$types];

        $return = [];
        foreach ($types as $key => $type) {
            $find = Arr::first(self::listNotificationType(), function ($value, $key) use ($type) {
                return (new \ReflectionClass($value['class']))->getShortName() === $type;
            });

            if (!empty($find)) {
                $return[] = (new \ReflectionClass($find['class']))->getName();
            }
        }
        return $return;
    }

    /**
    * Format readable notification type
    *
    * @param string $type
    * @return string
    */
    public function readableNotificationType($type)
    {
        $notifText = $type;
        if (empty($type)) {
            return $notifText;
        }
        $classNotif = (new \ReflectionClass($type))->getShortName();

        $result = Arr::first(self::listNotificationType(), function ($value, $key) use ($classNotif) {
            return (new \ReflectionClass($value['class']))->getShortName() === $classNotif;
        });

        $notifText = Arr::get($result, 'name', $notifText);
        return $notifText;
    }

    /**
    * Format readable notification data
    *
    * @param mixed $data
    * @return mixed
    */
    public function readableNotificationData($data)
    {
        if (empty($data)) {
            return false;
        }

        $data->message_html = "";
        if (!empty($data->message)) {
            $data->message_html = $this->proseMirrorToHTML($data->message);
        }
        $data->action_url = $this->readableActionUrlData();
        $data->actions = $this->readableActionsData();

        return $data;
    }

    /**
    * Convert prose mirror to HTML
    *
    * @param string|null $json
    * @param bool $lazyImg
    * @return string
    */
    public function proseMirrorToHTML($json, $lazyImg = false)
    {
        if (empty($json)) {
            return "";
        }

        $renderer = new Renderer();

        if (!$lazyImg) {
            $renderer->addImageNotLazy();
        }
        $renderer->setContent($json);
        $content = $renderer->getHTML();
        return $content;
    }

    /**
     * Format action url notif by notif data
     *
     * @return string|null
     */
    public function readableActionUrlData()
    {
        $actionUrl = null;
        if (empty($this->type)) {
            return $actionUrl;
        }

        $classNotif = (new \ReflectionClass($this->type))->getShortName();
        switch ($classNotif) {
            case "GeneralNotif":
                $actionUrl = null;
                break;
            case "ActivityAboutUserNotif":
                $actionUrl = $this->actionUrlInActivityNotif();

                break;
            case "SystemNotif":
                $actionUrl = $this->defaultNotificationUrl();
                break;

            default:
                $actionUrl = null;
                break;
        }
        return $actionUrl;
    }

    /**
     * Format actions data by notif data
     *
     * @return array|null
     */
    public function readableActionsData()
    {
        return optional($this->data)->actions;
        $actions = [];
        if (empty($this->type)) {
            return $actions;
        }

        $classNotif = (new \ReflectionClass($this->type))->getShortName();
        switch ($classNotif) {
            case "SystemNotif":
                $actions = $this->actionUrlInSystemNotif();
                break;

            default:
                $actions = [];
                break;
        }
        return $actions;
    }

    /**
    * Format action url notif by notif type activity user
    *
    * @return string|null
    */
    protected function actionUrlInActivityNotif()
    {
        $defaultActionUrl = $this->defaultNotificationUrl();

        if (empty($this->data)) {
            return $defaultActionUrl;
        }
        if (empty($this->data->notif_group)) {
            return $defaultActionUrl;
        }
        if (empty($this->data->id)) {
            return $defaultActionUrl;
        }

        $id = $this->data->id;

        $result = "";
        switch (strtolower($this->data->notif_group)) {
            default:
                $result = $defaultActionUrl;
                break;
        }
        return $result;
    }

    /**
    * Format action url notif by notif type system
    *
    * @return array|null
    */
    protected function actionUrlInSystemNotif()
    {
        $defaultActionUrl = $this->defaultNotificationUrl();
        $defaultAction = [
            'name' => 'Aksi',
            'title' => 'Aksi',
            'url' => $defaultActionUrl,
            'routeName' => '',
            'params' => []
        ];
        $result = $defaultAction;

        $actions = optional($this->data)->actions;

        if (empty($actions)) {
            return $result;
        }

        $actions = Arr::wrap($actions);
        if (empty($actions)) {
            return $result;
        }

        $result = [];
        foreach ($actions as $key => $action) {
            $action = (array)$action;
            $params = Arr::get($action, 'params');

            $params = is_object($params) ? (array) $params  : $params;

            switch (strtolower(Arr::get($action, 'name'))) {
                case "user":
                    $routeName = "admin.user.export.download";
                    $title = 'Download Ekspor Data User';
                    break;
                default:
                    $result[] = $defaultAction;
                    break;
            }

            $result[] = [
                'name' => 'Download',
                'title' => $title,
                'url' => route($routeName, $params),
                'routeName' => $routeName,
                'params' => $params
            ];
        }
        return $result;
    }

    /**
     * Default notification url / action url
     *
     * @return string|null
     */
    public function defaultNotificationUrl()
    {
        $url = "";
        $auth = Auth::user();
        if (empty($auth)) {
            return $url;
        }

        $url = route('admin.profile.notification.all');
        return $url;
    }
}
