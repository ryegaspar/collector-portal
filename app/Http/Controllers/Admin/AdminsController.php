<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Admin;
use App\Rules\CollectOneId;
use App\Rules\UserEmail;
use App\Rules\Username;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Unifin\TableFilters\AdminAdminFilter;
use Unifin\Traits\Paginate;

class AdminsController extends Controller
{
    use Paginate;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:read admin')->only('index');
        $this->middleware('permission:create admin')->only('store');
        $this->middleware('permission:update admin')->only(['edit', 'update']);
    }

    /**
     * Display admin page
     *
     * @param AdminAdminFilter $adminUserFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(AdminAdminFilter $adminUserFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getAdmins($adminUserFilter);

            return response($response, 200);
        }

        return view('admin.admins');
    }

    /**
     * persists a new user
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store()
    {
        $request = $this->validateRequest();

        $response = Admin::createAdmin($request);

        return response($response, 200);
    }

    /**
     * get admin
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function edit($id)
    {
        if (request()->wantsJson()) {
            return response(Admin::with('roles')->find($id), 200);
        }
    }

    /**
     * Update the given resource.
     *
     * @param Admin $admin
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Admin $admin)
    {
        if (Auth::user()->id == $admin->id) {
            return response([], 403);
        }

        $validator = Validator::make(request()->all(), [
                'first_name'    => ['required'],
                'last_name'     => ['required'],
                'access_level'  => ['required'],
            ]
        );

        $validator->sometimes('site_id', 'required|numeric', function ($input) {
            return $input->access_level == 'site-manager' || $input->access_level == 'sub-site-manager' || $input->access_level == 'team-leader';
        });

        $validator->sometimes('sub_site_id', 'required|numeric', function ($input) {
            return ! ! $input->site_id && ($input->access_level == 'sub-site-manager' || $input->access_level == 'team-leader');
        });

        $request = $validator->validate();

        $role = $request['access_level'];
        if (! ($role == 'sub-site-manager' || $role == 'team-leader')) {
            $request['sub_site_id'] = null;
        }
        unset($request['access_level']);

        $admin->update($request);
        $admin->syncRoles($role);

        return response([], 201);
    }

    /**
     * validate request
     *
     * @return mixed
     */
    protected function validateRequest()
    {
        $validator = Validator::make(request()->all(), [
                'username'      => ['required', 'min:6', 'unique:admins,username', new Username],
                'email'         => ['required', 'email', 'unique:admins,email', new UserEmail],
                'first_name'    => ['required'],
                'last_name'     => ['required'],
                'tiger_user_id' => ['required', 'max:3', new CollectOneId],
                'access_level'  => ['required'],
            ]
        );

        $validator->sometimes('site_id', 'required|numeric', function ($input) {
            return $input->access_level == 'site-manager' || $input->access_level == 'sub-site-manager' || $input->access_level == 'team-leader';
        });

        $validator->sometimes('sub_site_id', 'required|numeric', function ($input) {
            return ! ! $input->site_id && ($input->access_level == 'sub-site-manager' || $input->access_level == 'team-leader');
        });

        return $validator->validate();
    }

    /**
     * get admin
     *
     * @param $adminUserFilter
     * @return mixed
     */
    protected function getAdmins($adminUserFilter)
    {
        $admins = Admin::tableFilters($adminUserFilter)->with('roles:id,name');

        $results = $this->paginate($admins);

        return $results;
    }
}
