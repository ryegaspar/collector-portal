<?php

namespace App\Models\Lynx;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Unifin\TableFilters\TableFilter;

class LetterRequest extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'dbr_no',
        'creator_name',
        'request_method',
        'type',
        'borrower_type',
        'notes',
        'collector_id',
        'fulfilled_by',
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
     * A letter request morphs (belongs) to Collector or Admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function requestable()
    {
        return $this->morphTo();
    }

    /**
     * A letter request belongs to letter request type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function types()
    {
        return $this->belongsTo(LetterRequestType::class, 'type');
    }

    /**
     * A letter request is fulfilled by an admin.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function checked_by()
    {
        return $this->belongsTo(Admin::class, 'fulfilled_by');
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
