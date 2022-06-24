<?php

namespace Bagoesz21\LaravelNotification\Models\Traits;

use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

trait BaseModelTrait
{
    /**
     * Query scope truncate text in column
     *
     * @param \Illuminate\Database\Eloquent\Builder $q
     * @param string $col
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectColTruncated($q, $col, $limit = 100)
    {
        $qry_truncated = "SUBSTRING(".$col.",1, ".$limit.")";
        $query = "case when length(".$col.") > ".$limit."
                    then concat(".$qry_truncated.", ' ...')
                    else ".$col." end as ".$col.'_truncated';
        return $q->addSelect(\DB::raw($query));
    }

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
        return $q->addSelect(self::getTablePrimaryKeyName(), 'name');
    }

    private function isUsesTimestamps()
    {
        if(!in_array(HasTimestamps::class, class_uses_recursive(get_called_class()), true))return false;

        return !$this->usesTimestamps();
    }

    public function scopeSelectTimestamp($q)
    {
        if(!$this->isUsesTimestamps())return $q;

        return $q->addSelect(
            $this->getCreatedAtColumn(),
            $this->getUpdatedAtColumn(),
        );
    }

    public function getCreatedAtAgoHumanAttribute()
    {
        if(!$this->isUsesTimestamps())return null;
        return \Carbon\Carbon::parse($this->created_at)->diffForHumans();
    }

    public function getUpdatedAtAgoHumanAttribute()
    {
        if(!$this->isUsesTimestamps())return null;
        return \Carbon\Carbon::parse($this->updated_at)->diffForHumans();
    }

    private function isUsesSoftDelete()
    {
        return in_array(SoftDeletes::class, class_uses_recursive(get_called_class()), true);
    }

    public function getDeletedAtAgoHumanAttribute()
    {
        if(!$this->isUsesSoftDelete())return null;
        return \Carbon\Carbon::parse($this->deleted_at)->diffForHumans();
    }

    public function scopeSelectSoftDelete($q)
    {
        if(!$this->isUsesSoftDelete())return $q;
        if(!in_array(SoftDeletes::class, class_uses_recursive(get_called_class()), true))return $q;
        return $q->addSelect($this->getDeletedAtColumn());
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
        $conn = $this->getConnection();

        $selectRawRowNumber = null;
        if( $conn instanceOf \Illuminate\Database\PostgresConnection){
            $selectRawRowNumber = \DB::raw("row_number() over()");
        } else if( $conn instanceOf \Illuminate\Database\MySqlConnection) {
            \DB::statement(\DB::raw('set @row=0'));
            $selectRawRowNumber = \DB::raw("@row:=@row+1 as 'row_number'");
        }

        if($selectRawRowNumber)return $query;

        return $query->addSelect($selectRawRowNumber);
    }
}

