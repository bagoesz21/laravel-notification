<?php

namespace Bagoesz21\LaravelNotification\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Bagoesz21\LaravelNotification\Casts\JsonCast;
use Bagoesz21\LaravelNotification\Config\NotifConfig;
use EloquentFilter\Filterable;

class NotificationTemplate extends Model
{
    use HasFactory;
    use Traits\BaseModelTrait;
    use Filterable;

    protected $table = 'notification_templates';
    public $timestamps = true;
    protected $guarded = [];

    protected $casts = [
        'message' => JsonCast::class,
    ];

    public function getTable()
    {
        $tableName = NotifConfig::make()->get('tables.notification_template.table_name', null);

        if(is_null($tableName)){
            $tableName = parent::getTable();
        }
        return $tableName;
    }

    public function modelFilter()
    {
        return $this->provideFilter(Filters\NotificationTemplateFilter::class);
    }
}
