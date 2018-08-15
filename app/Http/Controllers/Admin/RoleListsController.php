<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class RoleListsController extends Controller
{
    /**
     * AdjustmentsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser', 'role:super-admin']);
    }

    /**
     * get all roles
     *
     * @return \Illuminate\Database\Eloquent\Collection|Role[]
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return Role::all();
        }
    }
}
