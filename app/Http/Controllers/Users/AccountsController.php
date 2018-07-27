<?php

namespace App\Http\Controllers\Users;

use App\DBR;
use Unifin\Traits\Paginate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\TableFilters\UserAccountFilter;

class AccountsController extends Controller
{
    use Paginate;

    /**
     * CollectionController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * show accounts page
     */
    public function index()
    {
        return view('users.accounts');
    }

    /**
     * display specified resource
     *
     * @param UserAccountFilter $userAccountFilter
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(UserAccountFilter $userAccountFilter)
    {
        $dbr = $this->getUserAccounts($userAccountFilter);

        if (request()->wantsJson()) {
            return $dbr;
        }
    }

    /**
     * fetch all relevant accounts for the user
     *
     * @param UserAccountFilter $userAccountFilter
     * @return mixed
     */
    public function getUserAccounts($userAccountFilter)
    {
        $dbr = DBR::userAccounts()->tableFilters($userAccountFilter);

        $results = $this->paginate($dbr);

        return $results;
    }
}
