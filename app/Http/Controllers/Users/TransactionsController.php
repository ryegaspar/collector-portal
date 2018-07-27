<?php

namespace App\Http\Controllers\Users;

use App\DebterPayment;
use App\Http\Controllers\Controller;
use Unifin\TableFilters\UserTransactionFilter;
use Unifin\Traits\Paginate;

class TransactionsController extends Controller
{
    use Paginate;

    /**
     * create new instance of TransactionsController
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * show transactions page
     */
    public function index()
    {
        return view('users.transactions');
    }

    /**
     * display specified resource
     *
     * @param UserTransactionFilter $userTransactionFilter
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(UserTransactionFilter $userTransactionFilter)
    {
        $transactions = $this->getTransactions($userTransactionFilter);

        if (request()->wantsJson()) {
            return $transactions;
        }
    }

    /**
     * get transactions of the current user
     *
     * @param $userTransactionFilter
     * @return mixed
     */
    public function getTransactions($userTransactionFilter)
    {
        $transactions = DebterPayment::userAccounts()->tabulate($userTransactionFilter);

        $results = $this->paginate($transactions);

        return $results;
    }

    /**
     * override paginate to include additional search properties
     *
     * @param $model
     * @return mixed
     */
    public function paginate($model)
    {
        $request = request();

        $perPage = $request->has('per_page') ? (int)$request->per_page : null;

        $pagination = $model->paginate($perPage)->appends([
            'sort'     => $request->sort,
            'search'   => $request->search,
            'per_page' => $request->per_page,
            'status'   => $request->status,
            'paydate'  => $request->paydate,
        ]);

        return $pagination;
    }
}
