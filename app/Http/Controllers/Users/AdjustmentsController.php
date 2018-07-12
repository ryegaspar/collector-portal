<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdjustmentsController extends Controller
{
    /**
     * CollectionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * display adjustments page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('users.adjustments');
    }
}
