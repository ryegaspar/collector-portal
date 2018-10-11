<?php

namespace App\Http\Controllers\Collector;

use App\Models\Lynx\LetterRequestType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LetterRequestTypesController extends Controller
{
    /**
     * Create new instance of LetterRequestTypesController
     */
    public function __construct()
    {
        $this->middleware('collectorResetPassword');
    }

    /**
     * Display listing of the resource.
     */
    public function index()
    {
        if (request()->wantsJson()) {

            $letter_request_type = LetterRequestType::select('id', 'name')->where('active', true)->get();

            return response(compact('letter_request_type'), 200);
        }
    }
}
