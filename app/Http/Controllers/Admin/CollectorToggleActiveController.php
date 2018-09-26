<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Collector;

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
     * toggle user state
     *
     * @param Collector $collector
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Collector $collector)
    {
        $collector->active = ! $collector->active;
        $collector->save();

        return response([], 201);
    }
}
