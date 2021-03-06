<?php

namespace App\Models\Lynx;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Unifin\TableFilters\TableFilter;

class RefundRequest extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'creator_name',
        'account_no',
        'client_name',
        'consumer_name',
        'amount',
        'payment_date',
        'payment_type',
        'last_4_bank_no',
        'refund_status',
        'status_last_updated_by',
        'status_last_update_date',
        'notes',
    ];

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

    /**
     * A refund request entry morphs (belongs) to Collector or Admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function requestable()
    {
        return $this->morphTo();
    }

    /**
     * A refund request report sent entry is fulfilled by an admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checked_by()
    {
        return $this->belongsTo(Admin::class, 'status_last_updated_by');
    }

    /**
     * Fetch the created_at attribute as diffForHumans.
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
     * Fetch the updated_at attribute as diffForHumans.
     *
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {
        if (Carbon::parse($date)->diffInDays(Carbon::now()) <= 5) {
            return Carbon::parse($date)->diffForHumans();
        }

        return Carbon::parse($date)->toFormattedDateString();
    }
}
