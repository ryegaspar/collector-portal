<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmUserEmail;
use App\Rules\UserEmail;
use App\Rules\Username;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Unifin\TableFilters\AdminUserFilter;
use Unifin\Traits\Paginate;

class UsersController extends Controller
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
    public function index(AdminUserFilter $adminUserFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getUsers($adminUserFilter);
            return response()->json($response);
        }

        return view('admin.users');
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
            'access_level' => ['required']
        ]);

        $response = User::createUser($user);

        return response($response, 201);
    }

    /**
     * get user
     *
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(User $user)
    {
        if (request()->wantsJson()) {
            return response($user, 200);
        }
    }


    /**
     * update the given user
     *
     * @param User $user
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(User $user)
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
     * get users
     *
     * @param $adminUserFilter
     * @return mixed
     */
    protected function getUsers($adminUserFilter)
    {
        $adjustments = User::tableFilters($adminUserFilter);

        $results = $this->paginate($adjustments);

        return $results;
    }
}
