<?php

namespace App\Http\Controllers\Collector;

use App\Models\Lynx\Collector;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollectorResetPasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'activeUser']);
    }

    /**
     * Display the password reset form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('collector.password-reset');
    }

    /**
     * Reset the given collectors's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function reset(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required|confirmed|min:8'
        ]);

        $collector = Collector::find($request->user()->id);

        $validatedData['password'] = bcrypt($validatedData['password']);
        $validatedData['change_pass_at'] = new Carbon;

        $collector->update($validatedData);

        return redirect(route('collector.dashboard'));
    }
}
