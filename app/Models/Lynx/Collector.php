<?php

namespace App\Models\Lynx;

use App\Unifin\Classes\NewCollector;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Unifin\TableFilters\TableFilter;

class Collector extends Authenticatable
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
    protected $appends = ['full_name', 'status', 'commission_structure'];

    /**
     * convert columns to their appropriate types
     *
     * @var array
     */
    protected $casts = [
        'start_date'          => 'datetime:m/d/Y',
        'start_full_month_at' => 'datetime:m/d/Y',
        'active'              => 'boolean'
    ];

    /**
     * set username column
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * A collector has many letter requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function letter_requests()
    {
        return $this->morphMany(LetterRequest::class, 'requestable');
    }

    /**
     * A collector has many desk transfer requests.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function desk_transfer_requests()
    {
        return $this->morphMany(DeskTransferRequest::class, 'requestable');
    }

    /**
     * A collector belongs to a sub site.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sub_site()
    {
        return $this->belongsTo(Subsite::class, 'sub_site_id', 'id');
    }

    /**
     * A collector has team leader.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team_leader()
    {
        return $this->belongsTo(Admin::class, 'team_leader_id', 'id');
    }

    /**
     * Accessor for created_at
     *
     * @param $date
     * @return string
     */
    public function getStartDateAttribute($date)
    {
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
    public function getCommissionStructureAttribute()
    {
        return collect(config('unifin.collector_commission_structures'))[$this->commission_structure_id];
    }

    /**
     * Accessor to collector status.
     *
     * @param $value
     * @return mixed
     */
    public function getStatusAttribute()
    {
        return collect(config('unifin.collector_statuses'))[$this->status_id];
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

    /**
     * Create a new collector account.
     *
     * @param $validatedData
     */
    public static function createCollector($validatedData)
    {
        $ids = (new NewCollector($validatedData['sub_site_id'], $validatedData['first_name'],
            $validatedData['last_name']))
            ->generateId();

        $startDate = Carbon::parse($validatedData['start_date']);
        $tempDate = Carbon::parse($validatedData['start_date'])->day(1);
        $fifteenth = Carbon::parse($validatedData['start_date'])->day(15);
        $validatedData['start_full_month_date'] = Carbon::parse($startDate) <= $fifteenth ? $tempDate : $tempDate->addMonth();

        $validatedData['desk'] = $ids[0];
        $validatedData['tiger_user_id'] = $ids[1];
        $validatedData['username'] = $ids[2];
        $validatedData['group'] = Subsite::find($validatedData['sub_site_id'])->default_collector_group;

        self::create($validatedData);
    }
}
