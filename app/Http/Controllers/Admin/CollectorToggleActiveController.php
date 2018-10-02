<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Collector;
use Carbon\Carbon;

class CollectorToggleActiveController extends Controller
{
    /**
     * UserToggleActiveController constructor.
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
        if (is_null($collector->date_terminated))
            $collector->date_terminated = new Carbon;
        else
            $collector->date_terminated = null;

        $collector->save();
        return response([], 201);
    }
}
