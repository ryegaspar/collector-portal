<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Unifin\Repositories\RawQueries;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * display dashboard page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $accountSummary = RawQueries::UserAccountSummary();

        return view('users.dashboard', compact('accountSummary'));
    }
}
