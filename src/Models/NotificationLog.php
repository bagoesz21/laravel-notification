<?php

namespace Bagoesz21\LaravelNotification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use EloquentFilter\Filterable;
use Bagoesz21\LaravelNotification\Config\NotifConfig;

class NotificationLog extends Model
{
    use HasFactory;
    use Traits\BaseModelTrait;
    use Traits\NotificationTrait;
    use Filterable;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'notification_logs';
    public $timestamps = true;
    protected $guarded = [];

    protected $casts = [
    ];

    public function getTable()
    {
        $tableName = NotifConfig::make()->get('tables.notification_log.table_name', null);

        if(is_null($tableName)){
            $tableName = parent::getTable();
        }
        return $tableName;
    }

    public function modelFilter()
    {
        return $this->provideFilter(Filters\NotificationFilter::class);
    }

    public function notifiable()
    {
        return $this->morphTo();
    }
}
