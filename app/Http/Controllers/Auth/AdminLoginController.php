<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * redirect after loging in
     *
     * @var string
     */
    protected $redirectTo = '../../admin/dashboard';

    /**
     * set decay minutes for login throttling
     *
     * @var int
     */
    public $decayMinutes = 20;

    /**
     * create AdminLoginController instance.
     *
     * AdminLoginController constructor.
     */
    public function __construct()
    {
        $this->middleware(['guest','guest:admin'], ['except' => 'logout']);
    }

    /**
     * show the application's login form
     *
     */
    public function showLoginForm()
    {
        return view('auth.adminlogin');
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
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials = array_add($credentials, 'active', '1');
        return $credentials;
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/admin');
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
