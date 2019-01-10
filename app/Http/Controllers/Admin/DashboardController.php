<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiger\UFN_TodayTotals;
use Unifin\Repositories\RawQueries;

class DashboardController extends Controller
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
        $postToday = UFN_TodayTotals::all();
        $hoursWorked = RawQueries::CollectorHoursWorked();

        return view('admin.dashboard', compact('postToday', 'hoursWorked'));
    }

    /**
     * Get Todays total setup summary.
     *
     * @return mixed
     */
}