<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Admin;
use Illuminate\Support\Facades\Auth;

class AdminToggleActiveController extends Controller
{
    /**
     * UserToggleActiveController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:disable users')->only('update');
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

        $admin->active = !$admin->active;
        $admin->save();

        return response([], 201);
    }
}
