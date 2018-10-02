<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Tiger\DebterPayment;
use Unifin\TableFilters\CollectorTransactionFilter;
use Unifin\Traits\Paginate;

class TransactionsController extends Controller
{
    use Paginate;

    /**
     * create new instance of TransactionsController
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }

    /**
     * show transactions page
     */
    public function index()
    {
        return view('collector.transactions');
    }

    /**
     * display specified resource
     *
     * @param CollectorTransactionFilter $userTransactionFilter
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(CollectorTransactionFilter $userTransactionFilter)
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
