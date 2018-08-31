<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Site;
use Spatie\Permission\Models\Role;

class AdminOptionsController extends Controller
{
    /**
     * AdminOptionsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser', 'permission:read admin']);
    }

    /**
     * get all roles
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        if (request()->wantsJson()) {

            $roles = Role::all()->pluck('name');

            $sites = Site::with('subSite')->get();

            return Response(compact('roles', 'sites'), 200);
        }
    }
}
