<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiger\CHK1;
use Carbon\Carbon;
use Unifin\Repositories\RawQueries;

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
        $sif = CHK1::where('DBR_STATUS','SIF')
                    ->orderBy('DBR_NO', 'desc')
                    ->take(100)
                    ->get();
        return view('admin.operationalreports')->with('sif', $sif);
    }
    
}