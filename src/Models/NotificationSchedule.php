<?php

namespace Bagoesz21\LaravelNotification\Models;

use App\Models\User;
use Bagoesz21\LaravelNotification\Models\Collections\NotificationScheduleCollection;
use EloquentFilter\Filterable;
use Thomasjohnkane\Snooze\Models\ScheduledNotification as Model;

class NotificationSchedule extends Model
{
    use Filterable;
    use Traits\BaseModelTrait;
    use Traits\NotificationTrait;

    protected $table = 'notification_schedules';

    public $timestamps = true;

    protected $guarded = [];

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo */
    public function user()
    {
        //->where('target_type', (new \ReflectionClass(User::class))->getName())
        return $this->belongsTo(User::class, 'target_id');
    }

    public function newCollection(array $models = [])
    {
        return new NotificationScheduleCollection($models);
    }

    public function getTargetTypeTextAttribute()
    {
        $targetTypeText = $this->target_type;
        if (empty($this->target_type)) {
            return $targetTypeText;
        }
        $classNotif = (new \ReflectionClass($this->target_type))->getShortName();
        switch ($classNotif) {
            case 'User':
                $targetTypeText = 'User';
                break;
            default:
                $targetTypeText = $this->target_type;
                break;
        }

        return $targetTypeText;
    }

    public function getNotificationTypeTextAttribute()
    {
        return $this->readableNotificationType($this->notification_type);
    }

    public function getUnserializeNotificationAttribute()
    {
        return $this->serializer->unserialize($this->notification);
    }

    public function getUnserializeTargetAttribute()
    {
        return $this->serializer->unserialize($this->target);
    }

    public function toMapResource()
    {
        return new \Bagoesz21\LaravelNotification\Http\Resources\NotificationScheduleResource($this);
    }

    public function modelFilter()
    {
        return $this->provideFilter(Filters\NotificationScheduleFilter::class);
    }
}
