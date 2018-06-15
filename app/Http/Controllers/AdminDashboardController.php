<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * create a new instance of AdminDashboardController
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * display dashboard index
     *
     * @return string
     */
    public function index()
    {
        return response([], 200);
    }
}
