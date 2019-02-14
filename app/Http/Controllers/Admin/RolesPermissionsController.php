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
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:read roles_permission')->only(['index', 'show']);
        $this->middleware('permission:update roles_permission')->only('update');
    }

    /**
     * display adjustments page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $permissions = collect([
            'Adjustments'            => [
                'view' => 'read adjustment'
            ],
            'Calendars'              => [
                'view' => 'read calendar',
            ],
            'Closure Reports'        => [
                'view' => 'view closure-report',
            ],
            'Collector'              => [
                'view'    => 'read collector',
                'create'  => 'create collector',
                'edit'    => 'update collector',
                'disable' => 'disable collector',
            ],
            'Collector Batches'      => [
                'view'   => 'read collector-batch',
                'create' => 'create collector-batch',
                'delete' => 'delete collector-batch',
            ],
            'Desk Transfer Requests' => [
                'review' => 'review desk-transfer-request',
            ],
            'Letter Request Types'   => [
                'view'    => 'read letter-request-type',
                'create'  => 'create letter-request-type',
                'edit'    => 'update letter-request-type',
                'disable' => 'disable letter-request-type',
            ],
            'Scripts'                => [
                'view'   => 'read script',
                'create' => 'create script',
                'edit'   => 'update script',
                'delete' => 'delete script'
            ]
        ]);

        $lists = collect();

        $permissions->flatten(1)->each(function ($item) use ($lists) {
            $lists[$item] = false;
        });

        return view('admin.roles-permissions', compact('permissions', 'lists'));
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

        array_filter($request->permissions, function ($key, $value) use (&$newPermissions) {
            if ($key) {
                $newPermissions[] = $value;
            }
        }, ARRAY_FILTER_USE_BOTH);

        Role::findByName($role)->syncPermissions($newPermissions);

        return response([], 201);
    }
}
