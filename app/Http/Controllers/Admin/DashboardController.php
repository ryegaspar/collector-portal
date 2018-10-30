<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        $todaysTotals = RawQueries::AdminTodayTotals();
        
        return view('admin.dashboard', compact('todaysTotals'));
    }
}
