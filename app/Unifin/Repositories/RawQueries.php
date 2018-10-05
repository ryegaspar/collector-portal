<?php

namespace Unifin\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RawQueries
{
    public static function UserAccountSummary()
    {
        $accountSummary = DB::connection('sqlsrv2')
            ->table('UFN.PaymentTable')
            ->leftJoin('CDS.DBR', 'UFN.PaymentTable.PAY_DBR_NO', '=', 'CDS.DBR.DBR_NO')
            ->select(DB::raw("count(UFN.PaymentTable.PAY_DBR_NO) as num_dbr, UFN.PaymentTable.Client_Name, sum(CDS.DBR.DBR_ASSIGN_AMT) as sum_assigned_amt, sum(CDS.DBR.DBR_RECVD_TOT) as sum_received_total"))
            ->where('DESK', request()->user()->desk)
            ->whereDate('UFN.PaymentTable.PAY_DATE_O', '>=', request()->user()->start_date)
            ->where('PAY_STATUS', 'T')
            ->groupBy('UFN.PaymentTable.Client_Name')
            ->orderBy('num_dbr', 'desc')
            ->get();
//            ->toSql();
//        dd($accountSummary);

        return $accountSummary;
    }

    public static function UserMonthlyTransactions($request)
    {
        $startDate = Carbon::parse('first day of this month')->toDateString();
        $endDate = Carbon::parse('last day of this month')->toDateString();

        if ($request->date) {
            $startDate = Carbon::parse("first day of {$request->date}")->toDateString();
            $endDate = Carbon::parse("last day of {$request->date}")->toDateString();
        }

        $transactions = DB::connection('sqlsrv2')
            ->table('UFN.PaymentTable')
            ->select(DB::raw('sum(PAY_AMT) as trs_payment_amount, sum(PAY_COMM) as trs_payment_comm_amount'))
            ->where('DESK', $request->user()->desk)
//            ->whereBetween('PAY_DATE_O', [$startDate, $endDate])
            ->whereDate('PAY_DATE_O', '>=', $request->user()->start_date)
            ->whereDate('PAY_DATE_O', '>=', $startDate)
            ->whereDate('PAY_DATE_O', '<=', $endDate)
            ->where('PAY_STATUS', 'T')
            ->get();

        $pdc = DB::connection('sqlsrv2')
            ->table('UFN.PaymentTable')
            ->select(DB::raw('sum(PAY_AMT) as pdc_payment_amount, sum(PAY_COMM) as pdc_payment_comm_amount'))
            ->where('DESK', $request->user()->desk)
            ->whereDate('PAY_DATE_O', '>=', $request->user()->start_date)
            ->whereBetween('PAY_DATE_O', [$startDate, $endDate])
            ->where('PAY_STATUS', '<>', 'T')
            ->get();

        return [$transactions, $pdc];
    }
}