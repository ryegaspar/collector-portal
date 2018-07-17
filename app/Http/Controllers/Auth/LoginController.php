<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\USR;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        request()->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = USR::where('USR_CODE', strtoupper($request->username))
            ->where('USR_PW', $request->password)
            ->where('USR_DEF_MOT_DESK', '!=', '')
            ->first();

        if ($user) {
            Auth::loginUsingId($user->USR_CODE);
            return $this->sendLoginResponse($request);
        } else {
            return response('invalid user', 401);
        }
    }

    /**
     * get the login username to be used by the controller
     *
     * @return string
     */
    public function username() {
        return 'USR_CODE';
    }

    /**
     * the user has been authenticated
     *
     * @param Request $request
     * @param $user
     * @return mixed
     */
    public function authenticated(Request $request, $user)
    {
        if ($request->wantsJson()) {
            return response()->json(['redirect' => $this->redirectTo], 200);
        }

        redirect()->intended($this->redirectPath());
    }
}
