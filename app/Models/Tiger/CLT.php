<?php

namespace App\Models\Tiger;

use Illuminate\Database\Eloquent\Model;

class CLT extends Model
{
    /**
     * define collect one db connection
     *
     * @var string
     */
    protected $connection = 'sqlsrv2';

    /**
     * define table for DBR
     *
     * @var string
     */
    protected $table = 'CDS.CLT';

    /**
     * define the primary key for the table
     *
     * @var string
     */
    protected $primaryKey = 'CLT_NO';

    /**
     * set incrementing of primary key to false
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * CLT belongs to dbr.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dbr()
    {
        return $this->belongsTo(DBR::class,'CLT_NO','DBR_CLIENT');
    }

}