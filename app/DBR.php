<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DBR extends Model
{
    /**
     * define table for DBR
     *
     * @var string
     */
    protected $table = 'CDS.DBR';

    /**
     * define the primary key for the table
     *
     * @var string
     */
    protected $primaryKey = 'DBR_NO';

    protected $casts = [
        'DBR_LAST_WORKED_O' => 'datetime:m/d/Y',
        'DBR_LAST_TRUST_DATE_O' => 'datetime:m/d/Y'
    ];

//    public function getDBR_LAST_WORKED_OAttribute($date) {
//        if (Carbon::parse($date)->diffInDays(Carbon::now()) <= 5) {
//            return Carbon::parse($date)->diffForHumans();
//        }
//        return Carbon::parse($date)->toFormattedDateString();
//    }
//
//    public function getAttribute($key) {
//       if (array_key_exists($prefixedKey = 'DBR_LAST_WORKED_O'))
//    }

//    public $incrementing = false;

//    /**
//     * a DBR belongs to a user
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
//     */
//    public function User()
//    {
//        return $this->belongsTo('App\DBR', 'desk', 'DBR_DESK');
//    }
}
