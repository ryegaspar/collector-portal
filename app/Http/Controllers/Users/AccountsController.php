<?php

namespace App\Http\Controllers\Users;

use App\DBR;
use Dildo\Traits\Paginate;
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
     * @param Request $request
     * @param DBR $dbr
     * @param UserAccountFilter $paginate
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request, DBR $dbr, UserAccountFilter $paginate)
    {
        $response = $dbr->getUserAccounts($request, $paginate);
        if ($request->wantsJson()) {
            return response()->json($response);
        }
    }
}
