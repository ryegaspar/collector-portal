<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class USR extends Authenticatable
{
    use Notifiable;

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
    protected $table = 'CDS.USR';

    /**
     * define the primary key for the table
     *
     * @var string
     */
    protected $primaryKey = 'USR_CODE';

    /**
     * set incrementing of primary key to false
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * disable remember token
     *
     * @return null|string
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * disable remember token
     *
     * @param string $value
     */
    public function setRememberToken($value)
    {
    }

    /**
     * disable remember token
     *
     * @return null|string
     */
    public function getRememberTokenName()
    {
        return null;
    }

    /**
     * disable remember token
     *
     * @param string $key
     * @param mixed $value
     * @return Authenticatable|void
     */
    public function setAttribute($key, $value)
    {
        $isRememberTokenAttribute = $key == $this->getRememberTokenName();
        if (!$isRememberTokenAttribute) {
            parent::setAttribute($key, $value);
        }
    }

}
