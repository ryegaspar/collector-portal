<?php

namespace App\Http\Controllers\Users;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardTransactionController extends Controller
{
    /**
     * DashboardTransactionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $startDate = Carbon::parse('first day of this month')->format("Y-m-d");
        $endDate = Carbon::parse('last day of this month')->format("Y-m-d");

        if ($request->date) {
            $startDate = Carbon::parse("first day of {$request->date}")->format("Y-m-d");
            $endDate = Carbon::parse("last day of {$request->date}")->format("Y-m-d");
        }

        $transactions = DB::table('UFN.PaymentTable')
            ->select(DB::raw('sum(PAY_AMT) as trs_payment_amount, sum(PAY_COMM) as trs_payment_comm_amount'))
            ->where('DESK', Auth::user()->USR_DEF_MOT_DESK)
            ->whereBetween('PAY_DATE_O', [$startDate, $endDate])
            ->where('PAY_STATUS', 'T')
            ->get();

        $pdc = DB::table('UFN.PaymentTable')
            ->select(DB::raw('sum(PAY_AMT) as pdc_payment_amount, sum(PAY_COMM) as pdc_payment_comm_amount'))
            ->where('DESK', Auth::user()->USR_DEF_MOT_DESK)
            ->whereBetween('PAY_DATE_O', [$startDate, $endDate])
            ->where('PAY_STATUS', '<>', 'T')
            ->get();

        return response(compact( 'transactions', 'pdc'), 200);
    }
}
