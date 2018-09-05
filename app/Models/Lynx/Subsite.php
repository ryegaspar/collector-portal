<?php

namespace App\Models\Lynx;

use Illuminate\Database\Eloquent\Model;
use Unifin\TableFilters\TableFilter;

class Subsite extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'site_id',
        'has_team_leaders',
        'description',
    ];

    /**
     * A subsite belongs to a Site.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * A subsite has many collectors.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function collectors()
    {
        return $this->hasMany(Collector::class, 'sub_site_id', 'id');
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
