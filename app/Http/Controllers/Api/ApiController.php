<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function clients()
    {
        $client_lists = config('unifin.client_lists');

        return response(compact('client_lists'), 200);
    }
}
