<?php

namespace App\Models\Lynx;

use App\Notifications\AccountCreated;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Unifin\TableFilters\TableFilter;

class Admin extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable;
    use CanResetPassword;
    use HasRoles;

    /**
     * Guard name for the model used for spatie laravel permissions
     *
     * @var string
     */
    protected $guard_name = 'admin';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'password',
        'last_name',
        'first_name',
        'email',
        'active',
        'confirmation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * append full name to the model
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * convert columns to their appropriate types
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean'
    ];

    /**
     * set username column
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * user has many scripts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function scripts()
    {
        return $this->hasMany('App\Script');
    }

    /**
     * fetch the created_at attribute as diffForHumans
     *
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        if (Carbon::parse($date)->diffInDays(Carbon::now()) <= 5) {
            return Carbon::parse($date)->diffForHumans();
        }

        return Carbon::parse($date)->toFormattedDateString();
    }

    /**
     * get full name of the user
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
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
     * create a new user
     *
     * @param $user
     * @return \App\Models\Lynx\Admin
     */
    public static function createUser($user)
    {
        $unencrypted_password = str_random(8);
        $user['password'] = bcrypt($unencrypted_password);

        $role = $user['access_level'];
        unset($user['access_level']);

        $userModel = self::create($user);
        $userModel->assignRole($role);

        $userModel->notify(new AccountCreated($userModel, $unencrypted_password));

        return $userModel;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * create a script for the user
     *
     * @param $script
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createScript($script)
    {
        if (request()->status) {
            $script['published_at'] = $this->freshTimestamp();
        }

        return $this->scripts()->create($script);
    }
}
