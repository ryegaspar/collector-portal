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

        $path = 'App\\Unifin\\Repositories\\ClientReporting\\';

        $reports = collect([
            'ffamPlacementAcknowledgement' => $path . 'FfamPlacementAcknowledgement',
            'hcuRemit' => $path . 'HcuRemit',
            'galaxyRemit' => $path . 'GalaxyRemit',
            'galaxyAPAY' => $path . 'GalaxyAPAY',
            'cascadeRemit' => $path . 'CascadeRemit',
            'asgRemit' => $path . 'AsgRemit',
            'plsRemit' => $path . 'PlsRemit',
            'pzaRemit' => $path . 'PzaRemit',
            'orionRemit' => $path . 'OrionRemit',
            'musiRemit' => $path . 'MusiRemit',
            'eosRemit' => $path . 'EosRemit',
            'rmcRemit' => $path . 'RmcRemit',
            'rtcRemit' => $path . 'RtcRemit',
            'wcrRemit' => $path . 'WcrRemit',
            'jcapMaintenance' => $path . 'JcapMaintenance',
            'resurgentRecap' => $path . 'ResurgentRecap',
            'resurgentRemit' => $path . 'ResurgentRemit',
            'resurgentSufWeekly' => $path . 'ResurgentSufWeekly',
            'resurgentSufDaily' => $path . 'ResurgentSufDaily',
            'resurgentSufMonthly' => $path . 'ResurgentSufMonthly',
            'resurgentPdc' => $path . 'ResurgentPdc',
            'resurgentBky' => $path . 'ResurgentBky',
            'resurgentKpi' => $path . 'ResurgentKpi',
            'resurgentDec' => $path . 'ResurgentDec',
            'resurgentFct' => $path . 'ResurgentFct',
            'resurgentBwr' => $path . 'ResurgentBwr',
            'resurgentWor' => $path . 'ResurgentWor',
            'resurgentAbl' => $path . 'ResurgentAbl',
            'jcapRecUni' => $path . 'JcapRecUni',
            'asgStatus' => $path . 'AsgStatus',
            'capioRemit' => $path . 'CapioRemit',
            'ltdRemit' => $path . 'LtdRemit',
            'pendrickMainInvoice' => $path . 'PendrickMainInvoice',
            'pendrickPcp2Invoice' => $path . 'PendrickPcp2Invoice',
            'pendrickIndirectPaymentsMain' => $path . 'PendrickIndirectPaymentsMain',
            'pendrickIndirectPaymentsPcp2' => $path . 'PendrickIndirectPaymentsPcp2',
            'claPayFile' => $path . 'ClaPayFile',
            'claStatus' => $path . 'ClaStatus',
            'asgPayFile' => $path . 'AsgPayFile'
        ]);


        $selectedReports = collect($request->all())->intersectByKeys($reports);

        $selectedReports->keys()->each(function ($item) use ($reports, $request) {
            (new $reports[$item])->generateReport($request);
        });


        return redirect()->back()->with('status', 'Success!')->withInput();
    }



}
