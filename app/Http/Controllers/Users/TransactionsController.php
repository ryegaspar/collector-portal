<?php

namespace App\Http\Controllers\Users;

use App\DebterPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\TableFilters\UserTransactionFilter;

class TransactionsController extends Controller
{
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
     * @param Request $request
     * @param DebterPayment $debterPayment
     * @param UserTransactionFilter $paginate
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, DebterPayment $debterPayment, UserTransactionFilter $paginate)
    {
        $response = $debterPayment->getUserPayments($request, $paginate);
//        if ($request->wantsJson()) {
            return response()->json($response);
//        }
    }
}
