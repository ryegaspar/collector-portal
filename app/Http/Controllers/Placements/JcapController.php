<?php

namespace App\Http\Controllers\Placements;

use App\Unifin\Repositories\Placements\Jcap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JcapController extends Controller
{
    public function index()
    {
//        dd((new Jcap(public_path('test\placements\UNI.test.NB.TXT'), 'some'))->getPlacements());

        return view('jcap.index');
    }

    public function show(Request $request)
    {
        $path = $request->file('placement')->storeAs('placements', $request->file('placement')->getClientOriginalName());

        //create csv
        (new Jcap($path, $request->file('placement')->getClientOriginalName()))->getCSV();

//        dd($path);
    }
}
