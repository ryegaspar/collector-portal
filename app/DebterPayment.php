<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Unifin\UserTabulation\TransactionsTabulations;

class DebterPayment extends Model
{
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
        'PAY_DATE_O' => 'datetime:m/d/Y'
    ];

    /**
     * apply tabulation to relevant debter payment
     *
     * @param $query
     * @param TransactionsTabulations $paginate
     * @return mixed
     */
    public function scopeTabulate($query, TransactionsTabulations $paginate)
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
     * @param TransactionsTabulations $debterPayment
     * @return mixed
     */
    public function getUserPayments($request, TransactionsTabulations $debterPayment)
    {
        $builder = DebterPayment::userAccounts()->tabulate($debterPayment);

        $perPage = $request->has('per_page') ? (int) $request->per_page : null;

        $pagination = $builder->paginate($perPage);
        $pagination->appends([
            'sort' => $request->sort,
            'search' => $request->search,
            'per_page' => $request->per_page
        ]);

        return $pagination;
    }
}
