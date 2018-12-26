<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Unifin\Repositories\RawQueries;

class TodaysTotalsController extends Controller
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
        $todaysTotalsDetail = RawQueries::TodaysTotalsDetail();

        return view('admin.todays-totals')->with('ttotals', $todaysTotalsDetail);
    }
}