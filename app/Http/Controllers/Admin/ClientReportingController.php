<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Unifin\Repositories\ClientReporting\ReportExcel;
use App\Http\Controllers\Controller;

class ClientReportingController extends Controller
{
 

    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

/*Default to set run report date based on day of week */
/* Monday = -1 to -3 */
/*Tuesday - Friday = -1 */
    public function index()
    {
        // $today = new Carbon();
        // if ($today->dayOfWeek == Carbon::MONDAY) {
        //     $startDate = Carbon::now()->addDay(-3);
        //     $endDate = Carbon::now()->addDay(-1);
        // } else {
        //     $startDate = $endDate = Carbon::now()->addDay(-1);
        // }

        return view('admin.clientreports');
    }



/*These are where the checkboxs from the view are connected to the Controller*/
    public function compute(Request $request)
    {
        
        $request->validate([
            'date1' => 'required|date',
            'date2' => 'required|date',
        ]);

        $reports = collect([
            'orionRemit' => 'App\\Unifin\\Repositories\\ClientReporting\\OrionRemit',
            'musiRemit' => 'App\\Unifin\\Repositories\\ClientReporting\\MusiRemit',
            'eosRemit' => 'App\\Unifin\\Repositories\\ClientReporting\\EosRemit',
            'rmcRemit' => 'App\\Unifin\\Repositories\\ClientReporting\\RmcRemit',
            'rtcRemit' => 'App\\Unifin\\Repositories\\ClientReporting\\RtcRemit',
            'wcrRemit' => 'App\\Unifin\\Repositories\\ClientReporting\\WcrRemit',
            'jcapMaintenance' => 'App\\Unifin\\Repositories\\ClientReporting\\JcapMaintenance',
            'resurgentRemit' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentRemit',
            'resurgentSufWeekly' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentSufWeekly',
            'resurgentSufDaily' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentSufDaily',
            'resurgentSufMonthly' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentSufMonthly',
            'resurgentPdc' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentPdc',
            'resurgentBky' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentBky',
            'resurgentKpi' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentKpi',
            'resurgentDec' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentDec',
            'resurgentFct' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentFct',
            'resurgentBwr' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentBwr',
            'resurgentWor' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentWor',
            'resurgentAbl' => 'App\\Unifin\\Repositories\\ClientReporting\\ResurgentAbl',
            'jcapRecUni' => 'App\\Unifin\\Repositories\\ClientReporting\\JcapRecUni',
            'asgStatus' => 'App\\Unifin\\Repositories\\ClientReporting\\AsgStatus',
            'capioRemit' => 'App\\Unifin\\Repositories\\ClientReporting\\CapioRemit',
            'ltdRemit' => 'App\\Unifin\\Repositories\\ClientReporting\\LtdRemit',
            'pendrickMainInvoice' => 'App\\Unifin\\Repositories\\ClientReporting\\PendrickMainInvoice',
            'pendrickPcp2Invoice' => 'App\\Unifin\\Repositories\\ClientReporting\\PendrickPcp2Invoice',
            'pendrickIndirectPaymentsMain' => 'App\\Unifin\\Repositories\\ClientReporting\\PendrickIndirectPaymentsMain',
            'pendrickIndirectPaymentsPcp2' => 'App\\Unifin\\Repositories\\ClientReporting\\PendrickIndirectPaymentsPcp2',
            'claPayFile' => 'App\\Unifin\\Repositories\\ClientReporting\\ClaPayFile',
            'asgPayFile' => 'App\\Unifin\\Repositories\\ClientReporting\\AsgPayFile'
        ]);

        $selectedReports = collect($request->all())->intersectByKeys($reports);

        $selectedReports->keys()->each(function ($item) use ($reports, $request) {
            (new $reports[$item])->generateReport($request);
        });


        return redirect()->back()->with('status', 'Success!')->withInput();
    }



}
