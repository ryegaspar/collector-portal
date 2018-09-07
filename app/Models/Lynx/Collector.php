<?php

namespace App\Models\Lynx;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Unifin\TableFilters\TableFilter;

class Collector extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'tiger_user_id',
        'desk',
        'username',
        'last_name',
        'first_name',
        'sub_site_id',
        'team_leader_id',
        'commission_structure_id',
        'start_date',
        'start_full_month_date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'change_pass_at',
        'remember_token',
        'password',
    ];

    /**
     * append full name to the model
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * convert columns to their appropriate types
     *
     * @var array
     */
    protected $casts = [
        'hire_at'             => 'datetime:m/d/Y',
        'start_full_month_at' => 'datetime:m/d/Y',
    ];

    /**
     * Fetch the subsite of a collector.
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
    public function getStartDateAttribute($date)
    {
        if (Carbon::parse($date)->diffInDays(Carbon::now()) <= 5) {
            return Carbon::parse($date)->diffForHumans();
        }

        return Carbon::parse($date)->toFormattedDateString();
    }

    /**
     * Accessor for start full month date.
     *
     * @param $date
     * @return string
     */
    public function getStartFullMonthDateAttribute($date)
    {
        return Carbon::parse($date)->toFormattedDateString();
    }

    /**
     * Accessor for commission structure name.
     *
     * @param $value
     * @return mixed
     */
    public function getCommissionStructureIdAttribute($value)
    {
        return collect(config('unifin.collector_commission_structures'))[$value];
    }

    /**
     * Accessor full name
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * apply filters for table view
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
