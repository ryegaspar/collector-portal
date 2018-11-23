<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiger\CHK;
use Carbon\Carbon;
use Unifin\Repositories\RawQueries;

class CollectorHoursController extends Controller
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
        $hoursWorkedDetail = RawQueries::CollectorHoursWorkedDetail();

//  dd($hoursWorkedDetail);
   //     dd($postToday);

        return view('admin.collector-hours', compact('hoursWorkedDetail'));
    }
}