<?php

namespace Bagoesz21\LaravelNotification\Models;

use \Illuminate\Notifications\DatabaseNotification as Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BaseModelTrait;
use Bagoesz21\LaravelNotification\Models\Collections\NotificationCollection;
use EloquentFilter\Filterable;
use Bagoesz21\LaravelNotification\Enums\NotificationLevel;

class Notification extends Model
{
    use HasFactory;
    use BaseModelTrait;
    use Traits\NotificationTrait;
    use Filterable;

    public $timestamps = true;
    protected $guarded = [];

    protected $casts = [
        'data' => \App\Casts\JsonCast::class,
        'level' => NotificationLevel::class
    ];

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
