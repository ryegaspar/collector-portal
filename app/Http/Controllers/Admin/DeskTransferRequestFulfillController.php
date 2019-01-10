<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\DeskTransferRequest;
use Illuminate\Support\Facades\DB;

class DeskTransferRequestFulfillController extends Controller
{
    /**
     * DesktTransferRequestTypeController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * Set the status of the specified resource to 1.
     *
     * @param DeskTransferRequest $deskTransferRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function approve(DeskTransferRequest $deskTransferRequest)
    {

        $deskTransferRequest->fulfilled_by = request()->user()->id;
        $deskTransferRequest->status = 1;

        $deskTransferRequest->save();

        $acct_no = $deskTransferRequest->dbr_no;
        $userid = strtoupper(request()->user()->tiger_user_id);
        $transferto = $deskTransferRequest->desk;

        DB::connection('sqlsrv2')->update("exec [UFN].[DeskTransferApprove] ?,?,?", [$acct_no, $userid, $transferto]);

        return response([], 201);
    }

    /**
     * Set the status of the specified resource to 2.
     *
     * @param DeskTransferRequest $deskTransferRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deny(DeskTransferRequest $deskTransferRequest)
    {
        $reason = chr(13) . chr(10) . "=============================" . chr(13) . chr(10) . "REJECT REASON:" . chr(13) . chr(10) . request()->reason . chr(13) . chr(10) . " - " . request()->user()->full_name;

        $deskTransferRequest->fulfilled_by = request()->user()->id;
        $deskTransferRequest->status = 2;
        $deskTransferRequest->notes = $deskTransferRequest->notes . $reason;

        $deskTransferRequest->save();

        $acct_no = $deskTransferRequest->dbr_no;
        $userid = strtoupper(request()->user()->tiger_user_id);
        $transferto = $deskTransferRequest->desk;

        DB::connection('sqlsrv2')->update("exec [UFN].[DeskTransferReject] ?,?,?", [$acct_no, $userid, $transferto]);

        return response([], 201);
    }

}
