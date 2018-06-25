<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

//    /**
//     * a user has many DBR
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     */
//    public function DBR()
//    {
//        return $this->hasMany('App\DBR', 'DBR_DESK', 'desk');
//    }
}
