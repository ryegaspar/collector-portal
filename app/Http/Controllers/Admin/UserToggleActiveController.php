<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class UserToggleActiveController extends Controller
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
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(User $user)
    {
        if ($user->id == 1) {
            return response([], 403);
        }

        if (Auth::user()->id == $user->id) {
            return response([], 403);
        }

        $user->active = !$user->active;
        $user->save();

        return response([], 201);
    }
}
