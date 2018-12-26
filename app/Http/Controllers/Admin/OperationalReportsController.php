<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiger\COLPDC;
use App\Models\Lynx\Collector;
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
        $two = COLPDC::all();
       
        return view('admin.operationalreports')->with('two', $two);
    }
    
}