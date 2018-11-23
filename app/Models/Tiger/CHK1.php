<?php

namespace App\Models\Tiger;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CHK1 extends Model
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
    protected $table = 'CDS.DBR';

    /**
     * define the primary key for the table
     *
     * @var string
     */
    protected $primaryKey = 'DBR_NO';

    
}