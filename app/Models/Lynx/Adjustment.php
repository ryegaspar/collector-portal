<?php

namespace App\Models\Lynx;

use App\Models\Tiger\DebterPayment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\TableFilter;

class Adjustment extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'desk',
        'desk_from',
        'name',
        'collector_name',
        'commission',
        'dbr_no',
        'amount',
        'reviewed_by',
        'date',
        'status'
    ];

    /**
     * accessors to append to the model's array form
     *
     * @var array
     */
    protected $appends = ['formatted_date', 'formatted_amount', 'formatted_commission'];

    /**
     * convert columns to their appropriate types
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime:m/d/Y',
    ];

    /**
     * An adjustment is reviewed by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reviewer()
    {
        return $this->belongsTo('App\Models\Lynx\Admin', 'reviewed_by', 'id');
    }

    /**
     * accessor to debter name, make it lower case
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->toFormattedDateString();
    }

    /**
     * accessor to the amount
     *
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, '.', ',');
    }

    /**
     * accessor to the commission
     *
     * @return string
     */
    public function getFormattedCommissionAttribute()
    {
        return number_format($this->commission, 2, '.', ',');
    }

    /**
     * fetch the created_at attribute as diffForHumans
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        if (Carbon::parse($date)->diffInDays(Carbon::now()) <= 5) {
            return Carbon::parse($date)->diffForHumans();
        }

        return Carbon::parse($date)->toFormattedDateString();
    }

    /**
     * get all dbr of the user
     *
     * @param $builder
     * @return mixed
     */
    public function scopeUserAdjustments($builder)
    {
        return $builder->where('desk', Auth::user()->desk);
    }

    /**
     * apply filters to relevant dbr
     *
     * @param $query
     * @param TableFilter $paginate
     * @return mixed
     */
    public function scopeTableFilters($query, TableFilter $paginate)
    {
        return $paginate->apply($query);
    }

    /**
     * add adjustments for the collector
     *
     * @param $adjustment
     * @return mixed
     */
    public static function addCollectorAdjustment($adjustment)
    {
        $adjustment['date'] = Carbon::parse(request()->date)->format('Y-m-d');

        $debterRecord = DebterPayment::getFirstRecord(request()->dbr_no, request()->amount, request()->date);

        $adjustment['commission'] = $debterRecord->PAY_COMM;
        $adjustment['name'] = $debterRecord->PAY_NAME;
        $adjustment['collector_name'] = Auth::user()->full_name;
        $adjustment['desk_from'] = $debterRecord->DESK;
        $adjustment['desk'] = Auth::user()->desk;

        return self::create($adjustment);
    }
}