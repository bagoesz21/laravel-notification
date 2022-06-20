<?php

namespace Bagoesz21\LaravelNotification\Models\Traits;

use DateTimeInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait BaseModelTrait
{
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Get table name of model statically
     *
     * @return string
     */
    public static function getTableName()
    {
        $class = get_called_class();
        return (new $class)->getTable();
    }

    /**
     * Get primary key name of model statically
     *
     * @return string
     */
    public static function getPrimaryKeyName()
    {
        $class = get_called_class();
        return (new $class())->getKeyName();
    }

    /**
     * Get table name + primary key of model statically
     *
     * @return string
     */
    public static function getTablePrimaryKeyName()
    {
        return self::getTableName() . "." . self::getPrimaryKeyName();
    }

    /**
     * Get columns in table of model statically
     *
     * @return array
     */
    public static function getListColoumn()
    {
        return \Illuminate\Support\Facades\Schema::getColumnListing(self::getTableName());
    }

    /**
     * Determine if the given relationship (method) exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasRelation($key)
    {
        // If the key already exists in the relationships array, it just means the
        // relationship has already been loaded, so we'll just return it out of
        // here because there is no need to query within the relations twice.
        if ($this->relationLoaded($key)) {
            return true;
        }

        // If the "attribute" exists as a method on the model, we will just assume
        // it is a relationship and will load and return results from the query
        // and hydrate the relationship's value on the "relationships" array.
        if (method_exists($this, $key)) {
            //Uses PHP built in function to determine whether the returned object is a laravel relation
            return is_a($this->$key(), 'Illuminate\Database\Eloquent\Relations\Relation');
        }

        return false;
    }

    public function scopeSelectDefault($q)
    {
        return $q->addSelect(self::getTableName().'.id', 'name');
    }

    public function scopeSelectTimestamp($q)
    {
        return $q->addSelect(
            !empty($this->CREATED_AT) ? $this->CREATED_AT : 'created_at',
            !empty($this->UPDATED_AT) ? $this->CREATED_AT : 'updated_at'
        );
    }

    public function scopeSelectSoftDelete($q)
    {
        return $q->addSelect(
            !empty($this->DELETED_AT) ? $this->deleted_at: 'deleted_at'
        );
    }

    public function scopeSelectDataDate($q)
    {
        return $q->selectTimestamp()->selectSoftDelete();
    }

    /**
     * Select row number
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $alias. Default = "no"
     * @return \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeSelectRowNumber($query, $alias = "no")
    {
        DB::statement(DB::raw('set @row=0'));

        return $query->addSelect(DB::raw('@row:=@row+1 as '.$alias));
    }

    public function getCreatedAtAgoHumanAttribute()
    {
        if (empty($this->created_at)) {
            return;
        }
        return Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getUpdatedAtAgoHumanAttribute()
    {
        if (empty($this->updated_at)) {
            return;
        }
        return Carbon::parse($this->updated_at)->diffForHumans();
    }

    public function getDeletedAtAgoHumanAttribute()
    {
        if (empty($this->deleted_at)) {
            return;
        }
        return Carbon::parse($this->deleted_at)->diffForHumans();
    }
}
