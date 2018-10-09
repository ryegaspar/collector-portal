<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lynx\LetterRequestType;
use App\Http\Controllers\Controller;

class LetterRequestTypeToggleActiveController extends Controller
{
    /**
     * UserToggleActiveController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:disable letter-request-type')->only(['update']);
    }

    /**
     * Toggle collector active/inactive.
     *
     * @param LetterRequestType $letterRequestType
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(LetterRequestType $letterRequestType)
    {
        $letterRequestType->active = !$letterRequestType->active;
        $letterRequestType->save();

        return response([], 201);
    }
}
