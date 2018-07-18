<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\UserAdjustmentFilter;

class Adjustment extends Model
{
    protected $fillable = ['desk', 'name', 'commission', 'dbr_no', 'amount', 'date'];

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
        'date'     => 'datetime:m/d/Y',
    ];

    /**
     * accessor to debter name, make it lower case
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->date)->toFormattedDateString();
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2, '.', ',');
    }

    public function getFormattedCommissionAttribute()
    {
        return number_format($this->commission, 2, '.', ',');
    }

    /**
     * get all dbr of the user
     *
     * @param $builder
     * @return mixed
     */
    public function scopeUserAdjustments($builder)
    {
        return $builder->where('desk', Auth::user()->USR_DEF_MOT_DESK);
    }

    /**
     * apply filters to relevant dbr
     *
     * @param $query
     * @param UserAdjustmentFilter $paginate
     * @return mixed
     */
    public function scopeTableFilters($query, UserAdjustmentFilter $paginate)
    {
        return $paginate->apply($query);
    }

    public static function addCollectorAdjustment($adjustment)
    {
        $adjustment['date'] = Carbon::parse(request()->date)->format('Y-m-d');

        $debterRecord = DebterPayment::getFirstRecord(request()->dbr_no, request()->amount, request()->date);

        $adjustment['commission'] = $debterRecord->PAY_COMM;
        $adjustment['name'] = $debterRecord->PAY_NAME;
        $adjustment['desk'] = Auth::user()->USR_DEF_MOT_DESK;

        return self::create($adjustment);
    }

    public function getUserAdjustments($request, UserAdjustmentFilter $paginate)
    {
        $builder = Adjustment::userAdjustments()->tableFilters($paginate);

        $perPage = $request->has('per_page') ? (int)$request->per_page : null;

        $pagination = $builder->paginate($perPage);
        $pagination->appends([
            'sort'     => $request->sort,
            'search'   => $request->search,
            'per_page' => $request->per_page
        ]);

        return $pagination;
    }
}
