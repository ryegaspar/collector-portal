<?php

namespace App\Models\Lynx;

use Illuminate\Database\Eloquent\Model;
use Unifin\TableFilters\TableFilter;

class Site extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * A site has many sub site.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subSite()
    {
        return $this->hasMany(Subsite::class);
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
