<?php

namespace Bagoesz21\LaravelNotification\Models;

use \Illuminate\Notifications\DatabaseNotification as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Bagoesz21\LaravelNotification\Models\Collections\NotificationCollection;
use EloquentFilter\Filterable;
use Bagoesz21\LaravelNotification\Enums\NotificationLevel;
use Bagoesz21\LaravelNotification\Casts\JsonCast;
use Bagoesz21\LaravelNotification\Config\NotifConfig;

class Notification extends Model
{
    use HasFactory;
    use Traits\BaseModelTrait;
    use Traits\NotificationTrait;
    use Filterable;

    public $timestamps = true;
    protected $guarded = [];

    protected $casts = [
        'data' => JsonCast::class,
        'level' => NotificationLevel::class
    ];

    public function getTable()
    {
        $tableName = NotifConfig::make()->get('tables.notification.table_name', null);

        if(is_null($tableName)){
            $tableName = parent::getTable();
        }
        return $tableName;
    }

    public function newCollection(array $models = [])
    {
        return new NotificationCollection($models);
    }

    public function getTypeTextAttribute(){
        return $this->readableNotificationType($this->type);
    }

    public function getFormattedDataAttribute(){
        return $this->readableNotificationData($this->data);
    }

    public function getFormattedActionUrlAttribute(){
        return $this->readableActionUrlData();
    }

    public function getIsReadAttribute(){
        return !empty($this->read_at) ? true : false;
    }

    public function getMessageHtmlAttribute(){
        return $this->proseMirrorToHTML($this->message);
    }

    public function getMessageHtmlLazyImgAttribute(){
        return $this->proseMirrorToHTML($this->message, true);
    }

    public function modelFilter()
    {
        return $this->provideFilter(Filters\NotificationFilter::class);
    }

    public function getImageUrlAttribute()
    {
        return url($this->image);
    }
}
