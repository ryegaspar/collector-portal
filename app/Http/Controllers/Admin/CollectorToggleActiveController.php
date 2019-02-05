<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Collector;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CollectorToggleActiveController extends Controller
{
    /**
     * CollectorToggleActiveController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:disable collector')->only(['update']);
    }

    /**
     * Toggle collector active/inactive.
     *
     * @param Collector $collector
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Collector $collector)
    {
        if (is_null($collector->date_terminated)){
            $collector->date_terminated = new Carbon;
            
            $name = $collector->last_name.', '.$collector->first_name;
            $collectordesk = $collector->desk;
            DB::connection('sqlsrv2')->update("exec [UFN].[TerminateCollectorAccounts] ?,?", [$name, $collectordesk]);
        }
        else {
            $activeCollectors = Collector::where('desk', $collector->desk)->whereNull('date_terminated')->count();
            if (!!$activeCollectors)
                return response([], 409);

            $collector->date_terminated = null;
        }

        $collector->save();

        return response([], 201);
    }
}
