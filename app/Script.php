<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Unifin\TableFilters\TableFilter;

class Script extends Model
{
    /**
     * attributes thar are mass assignable
     *
     * @var array
     */
    protected $fillable = ['title', 'content', 'author_id', 'status', 'published_at'];

    /**
     * the attributes that should be cast to native types
     *
     * @var array
     */
    protected $casts = ['status' => 'boolean'];

    /**
     * the attributes that are eager loaded
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * a script belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
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
     * fetch the created_at attribute as diffForHumans
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

    /**
     * fetch the published_at attribute as diffForHumans
     *
     * @param $date
     * @return string
     */
    public function getPublishedAtAttribute($date)
    {
        if (is_null($date)) {
            return 'Never';
        }

        if (Carbon::parse($date)->diffInDays(Carbon::now()) <= 5) {
            return Carbon::parse($date)->diffForHumans();
        }

        return Carbon::parse($date)->toFormattedDateString();
    }
}
