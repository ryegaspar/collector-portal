<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Unifin\Traits\Paginate;

class RolesPermissionsController extends Controller
{
    use Paginate;

    /**
     * UsersController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser', 'role:super-admin']);
    }

    /**
     * display adjustments page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.roles-permissions');
    }

    /**
     * Return specified resource.
     *
     * @param $role
     * @return \Illuminate\Support\Collection
     */
    public function show($role)
    {
        $role = Role::findByName($role);

        return $role->permissions->pluck('name');
    }

    /**
     * update the given user
     *
     * @param $role
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update($role, Request $request)
    {
        $newPermissions = [];

        if ($role == 'super-admin') {
            return response([], 403);
        }

        array_filter($request->permissions, function($key, $value) use (&$newPermissions) {
            if ($key) {
                $newPermissions[] = $value;
            }
        }, ARRAY_FILTER_USE_BOTH);

        Role::findByName($role)->syncPermissions($newPermissions);

        return response([], 201);
    }
}
