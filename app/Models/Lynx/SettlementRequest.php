<?php

namespace App\Models\Lynx;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Unifin\TableFilters\TableFilter;

class SettlementRequest extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'creator_name',
        'account_no',
        'client_ref_number',
        'client_name',
        'consumer_name',
        'account_status',
        'allowed_sif_amount',
        'allowed_sif_percent',
        'proposed_sif_amount',
        'proposed_sif_percent',
        'payment_method',
        'number_of_payments',
        'first_payment_date',
        'approval_reason',
        'status',
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
     * A settlement request entry morphs (belongs) to Collector or Admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function requestable()
    {
        return $this->morphTo();
    }

    /**
     * A settlement request report sent entry is fulfilled by an admin.
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
