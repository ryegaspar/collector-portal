<?php

namespace Unifin\Repositories\AdminReports;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TigerQueries
{
    public static function CollectorThreeMonthAverage()
    {
        $threemonthaverage = DB::connection('sqlsrv2')
            ->table('UFN.CollectorThreeMonthAverage')
            ->select(DB::raw("CollectorName, GroupName, DeskNumber, AverageTransactions, AverageFee, FirstMonth"))
            ->get();
//            ->toSql();
//        dd($threemonthaverage);

        return $threemonthaverage;
    }

    public static function AccountsInNewStatus()
    {
        $accountsinnew = DB::connection('sqlsrv2')
            ->table('UFN.AccountsInNewStatus')
            ->select(DB::raw("ClientCode, Category, Count, AverageBalance"))
            ->get();
//            ->toSql();
//        dd($accountsinnew);

        return $accountsinnew;
    }

}