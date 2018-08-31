<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Admin;
use App\Rules\UserEmail;
use App\Rules\Username;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        // no additional validation for access_level since the
        // 'users' control are only accessed by super-admins
        $user = $request->validate([
            'username'     => ['required', 'min:6', 'unique:users,username', new Username],
            'email'        => ['required', 'email', 'unique:users,email', new UserEmail],
            'first_name'   => ['required'],
            'last_name'    => ['required'],
        ]);

        $response = Admin::createUser($user);

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
     * update the given user
     *
     * @param Admin $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Admin $user)
    {
        if (Auth::user()->id == $user->id) {
            return response([], 403);
        }

        $newUser = request()->validate([
            'first_name'   => 'required',
            'last_name'    => 'required',
            'access_level' => 'required',
        ]);

        $role = $newUser['access_level'];
        unset($newUser['access_level']);

        $user->update($newUser);
        $user->syncRoles($role);

        return response([], 201);
    }

    /**
     * get admin
     *
     * @param $adminUserFilter
     * @return mixed
     */
    protected function getAdmins($adminUserFilter)
    {
        $admins = Admin::tableFilters($adminUserFilter)->with('roles');

        $results = $this->paginate($admins);

        return $results;
    }
}
