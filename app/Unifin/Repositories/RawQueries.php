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

    public static function SifClosures($startDate, $endDate)
    {
        /*
         *  SELECT "DBR"."DBR_NO", "DBR"."DBR_CLI_REF_NO", "DBR"."DBR_ASSIGN_AMT", "DBR"."DBR_RECVD_TOT", "TRS"."TRS_TRUST_CODE", "TRS"."TRS_TRX_DATE_O", "UDW"."UDW_FLD1", "UDW"."UDW_FLD2", "UDW"."UDW_FLD3", "UDW"."UDW_SEQ", "DBR"."DBR_STATUS", "CLT"."CLT_NOTE_TO_COLL", "DBR"."DBR_CLIENT", "DBR"."DBR_ASSIGN_DATE_O"
 FROM   ("tiger"."CDS"."UDW" "UDW" INNER JOIN ("tiger"."CDS"."TRS" "TRS" INNER JOIN "tiger"."CDS"."DBR" "DBR" ON "TRS"."TRS_DBR_NO"="DBR"."DBR_NO") ON "UDW"."UDW_DBR_NO"="DBR"."DBR_NO") INNER JOIN "tiger"."CDS"."CLT" "CLT" ON "DBR"."DBR_CLIENT"="CLT"."CLT_NO"
 WHERE  "UDW"."UDW_SEQ"='0ST' AND "TRS"."TRS_TRUST_CODE"<>0 AND  NOT ("DBR"."DBR_STATUS"='DUP' OR "DBR"."DBR_STATUS"='PIF' OR "DBR"."DBR_STATUS"='SIF' OR "DBR"."DBR_STATUS"='XCR') AND ("TRS"."TRS_TRX_DATE_O">={ts '2018-10-01 00:00:00'} AND "TRS"."TRS_TRX_DATE_O"<{ts '2018-10-15 00:00:01'})
         */

    }
    public static function CollectorHoursWorked()
    {
        $hoursWorked = DB::connection('sqlsrv2')
            ->table('UFN.CollectorHoursWorked')
            ->select(DB::raw("[UGP_DESC] as name,count(USR_NAME) as number, sum([Time_Worked]) as time, sum([Collector30Day]) as Collector30Day "))
            ->groupBy('UFN.CollectorHoursWorked.UGP_DESC')
            ->orderBy('UFN.CollectorHoursWorked.UGP_DESC', 'desc')
            ->get();
//            ->toSql();
//        dd($hoursWorked);

        return $hoursWorked;
    }
    public static function CollectorHoursWorkedDetail()
    {
        $hoursWorkedDetail = DB::connection('sqlsrv2')
            ->table('UFN.CollectorHoursWorked')
            ->select(DB::raw("[UGP_DESC] as name, [USR_NAME] as collector,[Team_Leader] as teamleader, [Time_Worked] as time, [Collector30Day] as Collector30Day"))
            ->orderBy('UFN.CollectorHoursWorked.UGP_DESC', 'desc')
            ->get();
//        dd($hoursWorkedDetail);

        return $hoursWorkedDetail;
    }
    public static function TodaysTotalsDetail()
    {
        $todaystotals = DB::connection('sqlsrv2')
            ->table('CDSMSC.CHK')
            ->Join('CDS.USR', 'CDSMSC.CHK.CHK_USERID', '=', 'CDS.USR.USR_CODE')
            ->Join('CDS.UGP', 'CDS.USR.USR_GROUP', '=', 'CDS.UGP.UGP_CODE')
            ->Join('CDS.DBR', 'CDS.DBR.DBR_NO', '=', 'CDSMSC.CHK.CHK_DBR_NO')
            ->Join('CDS.CLT', 'CDS.DBR.DBR_CLIENT', '=', 'CDS.CLT.CLT_NO')
            ->select(DB::raw("CHK_DBR_NO as DebtorNumber, DBR_CLIENT as ClientCode, CLT_NAME_1 as ClientName, CHK_CHECK_AMOUNT as CheckAmount, CHK_POST_DATE_O as PaymentDate, USR_NAME as UserName, UGP_DESC as CollectorGroup"))
            ->whereDate('CHK.EntryDate', '=', Carbon::now()->toDateString())
            ->whereDate('CHK.CHK_POST_DATE_O', '<=', Carbon::now()->addDays(30)->toDateString())
            ->orderBy('DebtorNumber', 'desc')
            ->get();
//            ->toSql();
//        dd($todaystotals);

        return $todaystotals;
    }
    public static function GetClientList()
    {
        $clientList = DB::connection('sqlsrv2')
            ->table('CDS.CLT')
            ->select(DB::raw("[CLT_NAME_1] as client_name1"))
            ->groupBy('CDS.CLT.CLT_NAME_1')
            ->get();

        return $clientList;
    }

}