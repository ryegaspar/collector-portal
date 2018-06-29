<?php

namespace App\Http\Controllers\Users;

use App\DBR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\UserTabulation\AccountsTabulation;

class AccountsController extends Controller
{
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
     * @param Request $request
     * @param DBR $dbr
     * @param AccountsTabulation $paginate
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, DBR $dbr, AccountsTabulation $paginate)
    {
        $response = $dbr->getUserAccounts($request, $paginate);
        if ($request->wantsJson()) {
            return response()->json($response);
        }
    }
}
