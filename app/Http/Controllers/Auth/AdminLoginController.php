<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '../../admin/dashboard';

    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => 'logout']);
    }

    /**
     * show the application's login form
     *
     */
    public function showLoginForm()
    {
        return 'hello';
    }

    /**
     * get the login username to be used by the controller
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * the user has been authenticated
     *
     * @param Request $request
     * @param $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticated(Request $request, $user)
    {
        if ($request->wantsJson()) {
            return response()->json(['redirect' => $this->redirectTo], 200);
        }

        redirect()->intended($this->redirectPath());
    }

    /**
     * get the guard to be used during authentication.
     *
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }
}
