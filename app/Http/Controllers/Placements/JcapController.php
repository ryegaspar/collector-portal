<?php

namespace App\Http\Controllers\Placements;

use App\Http\Controllers\Controller;
use App\Unifin\Repositories\Placements\Jcap;
use Illuminate\Http\Request;


class JcapController extends Controller
{
    public function index()
    {
        return view('admin.placements.jcap.index');
    }

    public function show(Request $request)
    {
        $request->file('placement')
            ->storeAs('public\placements', $request->file('placement')
                ->getClientOriginalName());

        $filename = $request->file('placement')->getClientOriginalName();
        $path = public_path('storage\\placements\\' . $filename);

        $records = (new Jcap($path, $filename))->getRecords();

        $csvFileName = public_path('storage\\placements\\' . $filename . '.csv');

        $fp = fopen($csvFileName, 'w');

        fputcsv($fp, Jcap::headers());

        foreach ($records as $row) {
            fputcsv($fp, $row);
        }

        fclose($fp);

        return response()->download($csvFileName);

//        return \Excel::create('jcap-placement', function ($excel) use ($records) {
//            $excel->sheet('placement', function ($sheet) use ($records) {
//                $sheet->fromArray($records);
//            });
//        })->download('csv');

//        return view('admin.placements.jcap.import', compact('records'));

//        dd($path);
    }
}
