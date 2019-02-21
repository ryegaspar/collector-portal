<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Unifin\Traits\Paginate;
use App\Models\Lynx\RemittanceLog;
use Illuminate\Http\Request;
use App\Rules\LetterRequestType;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\RemittanceLogFilter;
use App\Models\Tiger\TRS;
use Unifin\Repositories\RawQueries;
use Illuminate\Support\Facades\DB;


class RemittanceLogController extends Controller
{
    use Paginate;

    private $page = 'admin.remittance-log';

    /**
     * RemittanceLogController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * Display listing of the resource.
     *
     * @param RemittanceLogFilter $remittanceLogFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(RemittanceLogFilter $remittanceLogFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getRemittanceLog($remittanceLogFilter);

            return response()->json($response);
        }

        $clientList = RawQueries::GetClientList();

        return view($this->page, compact('clientList'));
    }

    public function indexdetailview($dataid) {   

        $remitdetail = RemittanceLog::where('id', $dataid)
                    ->get();

        $startdate = $remitdetail[0]['period_start_date'];
        $enddate = $remitdetail[0]['period_end_date'];
        $client = $remitdetail[0]['client_name'];

        $test = DB::connection('sqlsrv2')
                ->table('CDS.TRS')
                ->Join('CDS.CLT', 'CDS.TRS.TRS_AR_CLIENT', '=', 'CDS.CLT.CLT_NO')
                ->Join('CDS.TRC', 'CDS.TRS.TRS_TRUST_CODE', '=', 'CDS.TRC.TRC_CODE')
                ->select(DB::raw("CLT_NAME_1, SUM(TRS_AMT) as TRS_AMT, SUM(TRS_COMM_AMT) as TRS_COMM_AMT, SUM(IIF(TRS_TRUST_CODE=2,-TRS_COMM_AMT,TRS_AMT-TRS_COMM_AMT)) as TRS_REMIT_AMT"))
                ->whereDate('TRS_TRX_DATE_O', '>=', $startdate)
                ->whereDate('TRS_TRX_DATE_O', '<=', $enddate)
                ->where('CLT_NAME_1', $client)
                ->where('TRS_TRUST_CODE', '<>', 0)
                ->groupby('CLT_NAME_1')
                ->get();
       
        return view('admin.remittance-log-detail', compact('remitdetail', '1','2','3','test'));
    }

    public function indexstandardremit($client, $startdate, $enddate) {   

        $test = DB::connection('sqlsrv2')
        ->table('CDS.TRS')
        ->Join('CDS.CLT', 'CDS.TRS.TRS_AR_CLIENT', '=', 'CDS.CLT.CLT_NO')
        ->Join('CDS.TRC', 'CDS.TRS.TRS_TRUST_CODE', '=', 'CDS.TRC.TRC_CODE')
        ->select(DB::raw("CLT_NAME_1, TRS_DBR_NO, TRS_AMT, TRS_COMM_AMT, TRS_TRUST_CODE,TRC_DESC, TRS_TRX_DATE_O"))
        ->whereDate('TRS_TRX_DATE_O', '>=', $startdate)
        ->whereDate('TRS_TRX_DATE_O', '<=', $enddate)
        ->where('CLT_NAME_1', $client)
        ->where('TRS_TRUST_CODE', '<>', 0)
        ->get();

        dd($test);

       
        return view('admin.remittance-log-detail')->with('remitdetail', $remitdetail);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        $data = $this->validateData();

        $user = $request->user();

        //temporary fix, filter eager loaded in polymorphic relations not working in sqlsrv
        //TODO: remove if updated or fixed.
        $data['creator_name'] = $user->first_name . ' ' . $user->last_name;

        $startdate = $data['period_start_date'];
        $enddate = $data['period_end_date'];
        $client = $data['client_name'];

        (array)$databasevalidation = DB::connection('sqlsrv2')
                ->table('CDS.TRS')
                ->Join('CDS.CLT', 'CDS.TRS.TRS_AR_CLIENT', '=', 'CDS.CLT.CLT_NO')
                ->Join('CDS.TRC', 'CDS.TRS.TRS_TRUST_CODE', '=', 'CDS.TRC.TRC_CODE')
                ->select(DB::raw("CLT_NAME_1, SUM(TRS_AMT) as sumtrs, SUM(TRS_COMM_AMT) as TRS_COMM_AMT, SUM(IIF(TRS_TRUST_CODE=2,-TRS_COMM_AMT,TRS_AMT-TRS_COMM_AMT)) as TRS_REMIT_AMT"))
                ->whereDate('TRS_TRX_DATE_O', '>=', $startdate)
                ->whereDate('TRS_TRX_DATE_O', '<=', $enddate)
                ->where('CLT_NAME_1', $client)
                ->where('TRS_TRUST_CODE', '<>', 0)
                ->groupby('CLT_NAME_1')
                ->get();

        $array = json_decode(json_encode($databasevalidation), True);      

        if ($array[0]['sumtrs'] == $data['total_collections']) {

            $data['notes'] = $data['notes'] . 'Amount Validated';

        } else {

            $data['notes'] = $data['notes'] . 'Amount Not Validated';
        }

        $remittanceLog = new RemittanceLog($data);

        $response = $request->user()->remittance_logs()->save($remittanceLog);

        return response($response, 200);
    }

//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param $id
//     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
//     */
//    public function edit($id)
//    {
//        if (request()->wantsJson()) {
//            return response(RemittanceLog::find($id), 200);
//        }
//    }

    /**
     * Update the specified resource in storage.
     *
     * @param RemittanceLog $remittanceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(RemittanceLog $remittanceLog)
    {
        if (! Auth::user()->can('modify', $remittanceLog)) {

            return response(['message' => 'Not Allowed'], 403);
        }

        $validatedData = $this->validateData();

        $remittanceLog->update($validatedData);

        return response([], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param RemittanceLog $remittanceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(RemittanceLog $remittanceLog)
    {
        if (! Auth::user()->can('modify', $remittanceLog)) {

            return response(['message' => 'Not Allowed'], 403);
        }

        $remittanceLog->delete();

        return response([], 204);
    }

    /**
     * Get list of remittance log entries
     *
     * @param $remittanceLogFilter
     * @return mixed
     */
    private function getRemittanceLog($remittanceLogFilter)
    {
        $remittanceLog = RemittanceLog::tableFilters($remittanceLogFilter)
            ->with('requestable:id,tiger_user_id,last_name,first_name')
             ->with('checked_by:id,last_name,first_name');

        $results = $this->paginate($remittanceLog);

        return $results;
    }

    /**
     * Validate remittance log entry.
     *
     * @return mixed
     */
    private function validateData()
    {
        request();

        return request()->validate([
            'client_name'       => ['required'],
            'sub_client_name'   => [''],
            'remit_date'        => ['required'],
            'period_start_date' => ['required'],
            'period_end_date'   => ['required'],
            'total_collections' => ['required', 'numeric'],
            'total_client_collections'  => ['required', 'numeric'],
            'commission_amount' => ['required', 'numeric'],
            'remit_amount'  => ['required', 'numeric'],
            'notes'          => [''],
        ]);
    }
}
