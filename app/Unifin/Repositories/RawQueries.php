<?php

namespace Unifin\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RawQueries
{
    public static function UserAccountSummary()
    {
        $accountSummary = DB::table('UFN.PaymentTable')
            ->join('CDS.DBR', 'UFN.PaymentTable.PAY_DBR_NO', '=', 'CDS.DBR.DBR_NO')
            ->select(DB::raw('count(UFN.PaymentTable.PAY_DBR_NO) as num_dbr, UFN.PaymentTable.Client_Name, sum(CDS.DBR.DBR_ASSIGN_AMT) as sum_assigned_amt, sum(CDS.DBR.DBR_RECVD_TOT) as sum_received_total'))
            ->where('DESK', Auth::user()->USR_DEF_MOT_DESK)
            ->groupBy('UFN.PaymentTable.Client_Name')
            ->orderBy('num_dbr', 'desc')
            ->get();

        return $accountSummary;
    }

    public static function UserMonthlyTransactions($date = null)
    {
        $startDate = Carbon::parse('first day of this month')->format("Y-m-d");
        $endDate = Carbon::parse('last day of this month')->format("Y-m-d");

        if ($date) {
            $startDate = Carbon::parse("first day of {$date}")->format("Y-m-d");
            $endDate = Carbon::parse("last day of {$date}")->format("Y-m-d");
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

        return [$transactions, $pdc];
    }
}