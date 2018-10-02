<?php

namespace App\Http\Controllers\Collector;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\Traits\Paginate;

class LetterRequestController extends Controller
{
    use Paginate;

    /**
     * create new instance of ScriptsController
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }

    /**
     * show scripts page
     *
     * @param UserScriptFilter $scriptFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
//    public function index(UserScriptFilter $scriptFilter)
    public function index()
    {
//        if (request()->wantsJson()) {
//            $response = $this->getScripts($scriptFilter);
//
//            return response()->json($response);
//        }

        return view('collector.letter-requests');
    }
}
