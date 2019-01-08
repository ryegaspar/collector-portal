<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\RemittanceLog;
use Carbon\Carbon;

class RemittanceLogFulfillController extends Controller
{
    /**
     * RemittanceLogController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * Set the status of the specified resource to 1.
     *
     * @param RemittanceLog $remittanceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function reportapprove(RemittanceLog $remittanceLog)
    {
        $remittanceLog->report_sent_by = request()->user()->id;
        $remittanceLog->report_sent = 1;
        $remittanceLog->report_sent_date = Carbon::now()->toFormattedDateString();

        $remittanceLog->save();

        return response([], 201);
    }

        /**
     * Set the status of the specified resource to 1.
     *
     * @param RemittanceLog $remittanceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function remittanceapprove(RemittanceLog $remittanceLog)
    {
        $remittanceLog->remittance_sent_by = request()->user()->id;
        $remittanceLog->remittance_sent = 1;
        $remittanceLog->remittance_sent_date = Carbon::now()->toFormattedDateString();

        $remittanceLog->save();

        return response([], 201);
    }

        /**
     * Set the status of the specified resource to 1.
     *
     * @param RemittanceLog $remittanceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function commissionapprove(RemittanceLog $remittanceLog)
    {
        $remittanceLog->commission_recieved_by = request()->user()->id;
        $remittanceLog->commission_recieved = 1;
        $remittanceLog->commission_recieved_date = Carbon::now()->toFormattedDateString();

        $remittanceLog->save();

        return response([], 201);
    }

    /**
     * Set the status of the specified resource to 2.
     *
     * @param RemittanceLog $remittanceLog
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function addnotes(RemittanceLog $remittanceLog)
    {
        $reason = chr(13) . chr(10) . "=============================" . chr(13) . chr(10) . Carbon::now()->toFormattedDateString() . ":" . chr(13) . chr(10) . request()->reason . chr(13) . chr(10) . " - " . request()->user()->full_name;

        $remittanceLog->notes = $remittanceLog->notes . $reason;

        $remittanceLog->save();

        return response([], 201);
    }
}
