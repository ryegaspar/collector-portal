<?php

namespace App\Http\Controllers\Users;

use App\DebterPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * display dashboard page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $accountSummary = DB::table('UFN.PaymentTable')
            ->join('CDS.DBR', 'UFN.PaymentTable.PAY_DBR_NO', '=', 'CDS.DBR.DBR_NO')
            ->select(DB::raw('count(UFN.PaymentTable.PAY_DBR_NO) as num_dbr, UFN.PaymentTable.Client_Name, sum(CDS.DBR.DBR_ASSIGN_AMT) as sum_assigned_amt, sum(CDS.DBR.DBR_RECVD_TOT) as sum_received_total'))
            ->where('DESK', Auth::user()->USR_DEF_MOT_DESK)
            ->groupBy('UFN.PaymentTable.Client_Name')
            ->orderBy('num_dbr', 'desc')
            ->get();

        $startDate = Carbon::parse('first day of this month')->format("Y-m-d");
        $endDate = Carbon::parse('last day of this month')->format("Y-m-d");
        $monthName = Carbon::now()->format("F");

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

        return view('users.dashboard', compact('accountSummary', 'monthName', 'transactions', 'pdc'));
    }
}
