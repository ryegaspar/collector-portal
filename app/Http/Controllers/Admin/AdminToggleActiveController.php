<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Admin;
use App\Models\Lynx\Collector;
use Illuminate\Support\Facades\Auth;

class AdminToggleActiveController extends Controller
{
    /**
     * UserToggleActiveController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:disable admin')->only('update');
    }

    /**
     * toggle user state
     *
     * @param Admin $admin
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Admin $admin)
    {
        if ($admin->id == 1) {
            return response([], 403);
        }

        if (Auth::user()->id == $admin->id) {
            return response([], 403);
        }

        if ($admin->hasAnyRole('team-leader') && $admin->active) {
            if (Collector::where('team_leader_id', $admin->id)->where('active', true)->count() > 0)
                return response(['message' => 'Unable to disable team leader if it has people under it.'], 405);
        }

        $admin->active = !$admin->active;
        $admin->save();

        return response([], 201);
    }
}
