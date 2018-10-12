<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\LetterRequestType;

class ActiveLetterRequestTypesController extends Controller
{
    /**
     * Create new instance of LetterRequestTypesController
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
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
