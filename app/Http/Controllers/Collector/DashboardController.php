<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use Unifin\Repositories\RawQueries;

class DashboardController extends Controller
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
        $accountSummary = RawQueries::UserAccountSummary();
        $todaysTotals = RawQueries::AdminTodayTotals();

        return view('collector.dashboard', compact('accountSummary'), compact('todaysTotals'));
    }
}
