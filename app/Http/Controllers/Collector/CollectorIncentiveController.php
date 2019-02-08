<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Collector;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CollectorIncentiveController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }


    /**
     * display dashboard page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $ResurgentIncentive = DB::connection('sqlsrv2')
            ->table('UFN.PaymentTable')
            ->join('CDS.USR', 'CDS.USR.USR_DEF_MOT_DESK', '=', 'UFN.PaymentTable.DESK')
            ->select(DB::raw("USR_NAME as Name, DESK, SUM(IIF(PAY_STATUS = 'T',PAY_AMT,0)) as TransTotal, SUM(IIF(PAY_STATUS <> 'T',PAY_AMT,0)) as PDCTotal, SUM(PAY_AMT) as Total"))
            ->whereDate('UFN.PaymentTable.PAY_DATE_O', '>=', '02-11-2019')
            ->whereDate('UFN.PaymentTable.PAY_DATE_O', '<=', '02-28-2019')
            ->whereDate('UFN.PaymentTable.PAY_ENTRY_DATE', '>=', '02-11-2019')
            ->where('PAY_CLIENT', 'RCSOS1')
            ->groupBy('USR_Name', 'DESK')
            ->orderBy('Total', 'desc')
            ->get();
       
        return view('collector.collectorIncentive')->with('Incentive', $ResurgentIncentive);
    }
    
}