<?php

namespace App\Http\Controllers\Collector;

use App\Http\Controllers\Controller;
use App\Models\Tiger\COLPDC;
use App\Models\Lynx\Collector;
use Carbon\Carbon;
use Unifin\Repositories\RawQueries;

class CollectorPDCController extends Controller
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
        $two = COLPDC::all();
       
        return view('collector.collectorPDC')->with('two', $two);
    }
    
}