<?php

namespace App;

use App\Notifications\AccountCreated;
use App\Notifications\ResetPassword;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Unifin\TableFilters\TableFilter;

class User extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable;
    use CanResetPassword;

    protected $fillable = [
        'username',
        'password',
        'last_name',
        'first_name',
        'email',
        'access_level',
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
     * @return \App\User
     */
    public static function createUser($user)
    {
        $unencrypted_password = str_random(8);
        $user['password'] = bcrypt($unencrypted_password);

        $userModel = self::create($user);

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
}
