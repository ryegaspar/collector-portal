<?php

namespace App\Models\Lynx;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Unifin\TableFilters\TableFilter;

class CollectorBatch extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start_date',
        'sub_site_id',
    ];

    /**
     * convert columns to their appropriate types
     *
     * @var array
     */
    protected $casts = [
        'start_date'          => 'datetime:m/d/Y',
        'start_full_month_at' => 'datetime:m/d/Y',
    ];

    /**
     * A Collector Batch has many collectors
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collectors()
    {
        return $this->hasMany(Collector::class, 'batch_id', 'id');
    }

    /**
     * Fetch the subsite of a batch.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sub_site()
    {
        return $this->belongsTo(Subsite::class, 'sub_site_id', 'id');
    }

    /**
     * Accessor for created_at
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->toFormattedDateString();
    }

    /**
     * Apply filters for table view
     *
     * @param $query
     * @param TableFilter $paginate
     * @return mixed
     */
    public function scopeTableFilters($query, TableFilter $paginate)
    {
        return $paginate->apply($query);
    }
}