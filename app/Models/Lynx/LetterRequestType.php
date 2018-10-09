<?php

namespace App\Models\Lynx;

use Illuminate\Database\Eloquent\Model;
use Unifin\TableFilters\TableFilter;

class LetterRequestType extends Model
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
