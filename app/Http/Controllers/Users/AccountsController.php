<?php

namespace App\Http\Controllers\Users;

use App\DBR;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
     * show collection page
     */
    public function index()
    {
        return view('users.accounts');
    }

    public function show()
    {
        $query = DBR::where('DBR_DESK', Auth::user()->USR_DEF_MOT_DESK);
        $request = request();

        if (request()->filled('sort')) {
            // multisort
            $sorts = explode(',', request()->sort);
            foreach ($sorts as $sort) {
                list($sortCol, $sortDir) = explode('|', $sort);
                $query = $query->orderBy($sortCol, $sortDir);
            }
        } else {
            $query = $query->orderBy('DBR_NO', 'asc');
        }

        if (request()->exists('search')) {
            $query->where(function($q) use ($request) {
                $value = "%{$request->search}%";
                $q->where('DBR_NAME1', 'like', $value)
                    ->orWhere('DBR_NO', 'like', $value);
            });
        }

        $perPage = request()->has('per_page') ? (int) request()->per_page : null;

        $pagination = $query->paginate($perPage);
        $pagination->appends([
            'sort' => request()->sort,
            'filter' => request()->filter,
            'per_page' => request()->per_page
        ]);

        return response()->json($pagination);
    }
}
