<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
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
        $this->middleware('auth:admin');
    }

    /**
     * display adjustments page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('superadmin.users');
    }

    /**
     * return a lists of the resource in vuetable format
     *
     * @param AdminUserFilter $adminUserFilter
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(AdminUserFilter $adminUserFilter)
    {
        $response = $this->getUsers($adminUserFilter);
        if (request()->wantsJson()) {
            return response()->json($response);
        }
    }

    /**
     * get users
     *
     * @param $adminUserFilter
     * @return mixed
     */
    public function getUsers($adminUserFilter)
    {
        $adjustments = User::tableFilters($adminUserFilter);

        $results = $this->paginate($adjustments);

        return $results;
    }
}
