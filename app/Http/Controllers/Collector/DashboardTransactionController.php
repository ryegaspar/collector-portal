<?php

namespace App\Http\Controllers\Collector;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\Repositories\RawQueries;

class DashboardTransactionController extends Controller
{
    /**
     * DashboardTransactionController constructor.
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }

    /**
     * show user transactions on ajax request only
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        list($transactions, $pdc) = RawQueries::UserMonthlyTransactions($request);

        if ($request->wantsJson()) {
            return response(compact( 'transactions', 'pdc'), 200);
        }
    }
}
