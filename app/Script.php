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
    protected $fillable = ['title', 'content', 'admin_id', 'published_at'];

    /**
     * the attributes that are eager loaded
     *
     * @var array
     */
    protected $with = ['admin'];

    /**
     * A script belongs to an admin
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo('App\Models\Lynx\Admin');
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

    /**
     * create a script for the user
     *
     * @param $script
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function createScript($script)
    {
        if (request()->status) {
            $script['published_at'] = new Carbon;
        }
        $script['admin_id'] = request()->user()->id;

        return self::create($script);
    }
}
