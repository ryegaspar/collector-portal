<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiger\COLPDC;
use App\Models\Lynx\Collector;
use Carbon\Carbon;
use Unifin\Repositories\AdminReports\TigerQueries;

class OperationalReportsController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * display dashboard page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        return view('admin.operationalreports');
    }

    public function indexcollectorpdc()
    {
        $two = COLPDC::all();
       
        return view('admin.adminreports.collector-pdc')->with('two', $two);
    }

    public function indexcollectoraverage()
    {
        $threemonthaverage = TigerQueries::CollectorThreeMonthAverage();
       
        return view('admin.adminreports.collector-average')->with('threemonthaverage', $threemonthaverage);
    }

    public function indexaccountsinnewstatus()
    {
        $accountsinnew = TigerQueries::AccountsInNewStatus();
       
        return view('admin.adminreports.accounts-in-new-status')->with('accountsinnew', $accountsinnew);
    }

    
}