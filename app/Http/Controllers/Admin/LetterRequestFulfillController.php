<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\LetterRequest;

class LetterRequestFulfillController extends Controller
{
    /**
     * LetterRequestTypeController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * Set the status of the specified resource to 1.
     *
     * @param LetterRequest $letterRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function approve(LetterRequest $letterRequest)
    {
        $letterRequest->fulfilled_by = request()->user()->id;
        $letterRequest->status = 1;

        $letterRequest->save();

        return response([], 201);
    }

    /**
     * Set the status of the specified resource to 2.
     *
     * @param LetterRequest $letterRequest
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function deny(LetterRequest $letterRequest)
    {
        $reason = chr(13) . chr(10) . "=============================" . chr(13) . chr(10) . "REJECT REASON:" . chr(13) . chr(10) . request()->reason . chr(13) . chr(10) . " - " . request()->user()->full_name;

        $letterRequest->fulfilled_by = request()->user()->id;
        $letterRequest->status = 2;
        $letterRequest->notes = $letterRequest->notes . $reason;

        $letterRequest->save();

        return response([], 201);
    }
}
