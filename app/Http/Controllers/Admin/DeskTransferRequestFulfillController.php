<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\DeskTransferRequest;

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

        return response([], 201);
    }

}
