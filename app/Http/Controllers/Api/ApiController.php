<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function clients()
    {
        $clients = config('unifin.client_lists');

        return response(compact('clients'), 200);
    }
}
