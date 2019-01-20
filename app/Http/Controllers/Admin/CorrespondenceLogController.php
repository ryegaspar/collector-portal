<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Unifin\Traits\Paginate;
use App\Models\Lynx\CorrespondenceLog;
use Illuminate\Http\Request;
use App\Rules\LetterRequestType;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\CorrespondenceLogFilter;
use Unifin\Repositories\RawQueries;
use Carbon\Carbon;
use App\Models\Tiger\DBR;
use App\Models\Tiger\CLT;


class CorrespondenceLogController extends Controller
{
    use Paginate;

    private $page = 'admin.correspondence-log';

    /**
     * CorrespondenceLogController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * Display listing of the resource.
     *
     * @param CorrespondenceLogFilter $CorrespondenceLogFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(CorrespondenceLogFilter $correspondenceLogFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getCorrespondenceLog($correspondenceLogFilter);

            return response()->json($response);
        }

        $clientList = RawQueries::GetClientList();

        return view($this->page, compact('clientList'));
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
        
        //Obtain account information for database entry
        $debterRecord = DBR::findOrFail($data['account_no']);
        $clientRecord = CLT::findOrFail($debterRecord->DBR_CLIENT);
        $data['consumer_name'] = $debterRecord->DBR_NAME1;
        $data['client_name'] = $clientRecord->CLT_NAME_1;
        
        //Obtain user information for database entry
        $user = $request->user();
        $data['creator_name'] = $user->first_name . ' ' . $user->last_name;

        $data['correspondence_date'] = Carbon::now()->toFormattedDateString();

        //Obtain attachment information for database entry
        if ($request->file('attachment') <> 0) {
    
            $data['attachment_path'] = hash( 'sha256', time());
            $data['attachment_name'] = $request->file('attachment')->getClientOriginalName();
            $data['attachment_mime'] = $request->file('attachment')->getClientMimeType();
            $data['attachment_size'] = $request->file('attachment')->getClientSize();
            $request->file('attachment')->storeAs('public\correspondence', $data['attachment_path'] . '\\' . $data['attachment_name']);
       
        }
        
        $correspondenceLog = new CorrespondenceLog($data);
        $response = $request->user()->correspondence_logs()->save($correspondenceLog);

        return response([], 200);
        }
//        $data = $this->validateData();
//
//        $user = $request->user();
//
//        //temporary fix, filter eager loaded in polymorphic relations not working in sqlsrv
//        //TODO: remove if updated or fixed.
//        $data['creator_name'] = $user->first_name . ' ' . $user->last_name;
//
//        $correspondenceLog = new CorrespondenceLog($data);
//
//        $response = $request->user()->correspondence_logs()->save($correspondenceLog);
//
//        return response($response, 200);

    /**
     * Update the specified resource in storage.
     *
     * @param CorrespondenceLog $correspondenceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(CorrespondenceLog $correspondenceLog)
    {
        if (! Auth::user()->can('modify', $correspondenceLog)) {

            return response(['message' => 'Not Allowed'], 403);
        }

        $validatedData = $this->validateData();

        $correspondenceLog->update($validatedData);

        return response([], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CorrespondenceLog $correspondenceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function destroy(CorrespondenceLog $correspondenceLog)
    {
        if (! Auth::user()->can('modify', $correspondenceLog)) {

            return response(['message' => 'Not Allowed'], 403);
        }

        $correspondenceLog->delete();

        return response([], 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CorrespondenceLog $correspondenceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function downloadfile($path_file = null, $file = null) {   
        $path = storage_path().'/app/public/correspondence'.'/'.$path_file.'/'.$file;
        if(file_exists($path)) {
            return response()->download($path);
        }
    }  

            /**
     * Set the status of the specified resource to 1.
     *
     * @param CorrespondenceLog $correspondenceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updatestatus(CorrespondenceLog $correspondenceLog)
    {
        $correspondenceLog->status_last_updated_by = request()->user()->id;
        $correspondenceLog->status = $correspondenceLog->status + 1;
        $correspondenceLog->status_last_update_date = Carbon::now()->toFormattedDateString();

        $correspondenceLog->save();

        return response([], 201);
    }

    /**
     * Set the status of the specified resource to 2.
     *
     * @param CorrespondenceLog $correspondenceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addnotes(CorrespondenceLog $correspondenceLog)
    {
        $reason = chr(13) . chr(10) . "=============================" . chr(13) . chr(10) . Carbon::now()->toFormattedDateString() . ":" . chr(13) . chr(10) . request()->reason . chr(13) . chr(10) . " - " . request()->user()->full_name;

        $correspondenceLog->notes = $correspondenceLog->notes . $reason;

        $correspondenceLog->save();

        return response([], 201);
    }

    /**
     * Get list of Correspondence Log entries
     *
     * @param $correspondenceLogFilter
     * @return mixed
     */
    private function getCorrespondenceLog($correspondenceLogFilter)
    {
        $correspondenceLog = CorrespondenceLog::tableFilters($correspondenceLogFilter)
            ->with('requestable:id,tiger_user_id,last_name,first_name')
             ->with('checked_by:id,last_name,first_name');

        $results = $this->paginate($correspondenceLog);

        return $results;
    }

    /**
     * Validate Correspondence Log entry.
     *
     * @return mixed
     */
    private function validateData()
    {
        request();

        request()->merge(['account_no' => sprintf('%010d', request()->account_no)]);

        return request()->validate([
            'account_no'            => 'required|exists:sqlsrv2.CDS.DBR,DBR_NO',
            'correspondence_from'   => ['required'],
            'correspondence_type'   => ['required'],
            'correspondence_date'   => ['required'],
            'assigned_department'   => ['required'],
            'notes'                 => ''
        ]);
    }
}
