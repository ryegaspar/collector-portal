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
        $letterRequest->fulfilled_by = request()->user()->id;
        $letterRequest->status = 2;

        $letterRequest->save();

        return response([], 201);
    }
}
