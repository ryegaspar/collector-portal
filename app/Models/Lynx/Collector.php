<?php

namespace App\Models\Lynx;

use Illuminate\Database\Eloquent\Model;

class Collector extends Model
{
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'tiger_user_id',
        'desk',
        'username',
        'last_name',
        'first_name',
        'manager_id',
        'site_id',
        'sub_site_id',
        'manager_id',
        'team_leader_id',
        'hire_at',
        'status_id',
        'start_date',
        'start_full_month_at',
        'password'
    ];

    /**
     * convert columns to their appropriate types
     *
     * @var array
     */
    protected $casts = [
        'hire_at'             => 'datetime:m/d/Y',
        'start_full_month_at' => 'datetime:m/d/Y',
    ];
}
