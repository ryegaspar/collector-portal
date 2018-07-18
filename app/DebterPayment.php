<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\UserTransactionFilter;

class DebterPayment extends Model
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
    protected $table = 'UFN.PaymentTable';

    /**
     * convert columns to their appropriate types
     *
     * @var array
     */
    protected $casts = [
        'PAY_DATE_O' => 'datetime:m/d/Y',
    ];

    /**
     * accessors to append to the model's array form
     *
     * @var array
     */
    protected $appends = ['full_name', 'payment_type', 'pay_date', 'pay_commission', 'pay_amount'];

    /**
     * accessor to debter name, make it lower case
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return ucwords(strtolower(str_replace(',', ', ', $this->PAY_NAME)));
    }

    /**
     * accessor to payment type
     *
     * @return string
     */
    public function getPaymentTypeAttribute()
    {
        return ucwords(strtolower($this->PAY_TYPE));
    }

    /**
     * accessor to paydate, reformatted date to carbon instance
     *
     * @return string
     */
    public function getPayDateAttribute()
    {
        if ($this->PAY_DATE_O == '') {
            return 'never';
        }

        if ((Carbon::parse($this->PAY_DATE_O)->diffInDays(Carbon::now()) <= 5) &&
            (Carbon::parse($this->PAY_DATE_O)->diffInDays(Carbon::now()) >= 0)) {
            return Carbon::parse($this->PAY_DATE_O)->diffForHumans();
        }

        if ((Carbon::parse($this->PAY_DATE_O)->diffInDays(Carbon::now()) >= -5) &&
            (Carbon::parse($this->PAY_DATE_O)->diffInDays(Carbon::now()) < 0)) {
            return Carbon::parse($this->PAY_DATE_O)->diffForHumans();
        }

        return Carbon::parse($this->PAY_DATE_O)->toFormattedDateString();
    }

    /**
     * accessor to pay amount
     *
     * @return string
     */
    public function getPayAmountAttribute()
    {
        return number_format($this->PAY_AMT, 2, '.', ',');
    }

    /**
     * accessor to pay commission
     *
     * @return string
     */
    public function getPayCommissionAttribute()
    {
        return number_format($this->PAY_COMM, 2, '.', ',');
    }

    /**
     * accessor to payment date, reformatted date to carbon instance
     *
     * @return string
     */
    public function getPaymentDateAttribute()
    {
        if (Carbon::parse($this->PAY_DATE_O)->diffInDays(Carbon::now()) <= 5) {
            return Carbon::parse($this->PAY_DATE_O)->diffForHumans();
        }

        return Carbon::parse($this->PAY_DATE_O)->toFormattedDateString();
    }

    /**
     * apply tabulation to relevant debter payment
     *
     * @param $query
     * @param UserTransactionFilter $paginate
     * @return mixed
     */
    public function scopeTabulate($query, UserTransactionFilter $paginate)
    {
        return $paginate->apply($query);
    }

    /**
     * get all dbr of the user
     *
     * @param $builder
     * @return mixed
     */
    public function scopeUserAccounts($builder)
    {
        return $builder->where('DESK', Auth::user()->USR_DEF_MOT_DESK);
    }

    /**
     * fetch all relevant dbr accounts
     *
     * @param $request
     * @param UserTransactionFilter $debterPayment
     * @return mixed
     */
    public function getUserPayments($request, UserTransactionFilter $debterPayment)
    {
        $builder = DebterPayment::userAccounts()->tabulate($debterPayment);

        $perPage = $request->has('per_page') ? (int)$request->per_page : null;

        $pagination = $builder->paginate($perPage)->appends([
            'sort'     => $request->sort,
            'search'   => $request->search,
            'per_page' => $request->per_page,
            'status'   => $request->status,
            'paydate'  => $request->paydate,
        ]);

        return $pagination;
    }

    public static function getFirstRecord($dbr_no, $amount, $date)
    {
        $date = Carbon::parse($date)->toDateString();

        return self::where('PAY_DBR_NO', '=', $dbr_no)
            ->whereDate('PAY_DATE_O', '=', $date)
            ->where('PAY_AMT', '=', $amount)
            ->where('PAY_STATUS', '=', 'T')
            ->first();
    }
}
