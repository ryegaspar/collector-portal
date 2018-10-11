<?php

namespace App\Http\Controllers\Collector;

use App\Models\Lynx\Collector;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CollectorResetPasswordController extends Controller
{
    /**
     * Display the password reset form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (!is_null(Auth::user()->change_pass_at)) {
            return redirect()->route('collector.dashboard');
        }

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
        if (!is_null(Auth::user()->change_pass_at)) {
            return redirect()->route('collector.dashboard');
        }

        $validatedData = $request->validate([
            'password' => ['required', 'confirmed', 'min:8', 'notIn:Password1']
        ]);

        $collector = Collector::find($request->user()->id);

        $validatedData['password'] = bcrypt($validatedData['password']);
        $validatedData['change_pass_at'] = new Carbon;

        $collector->update($validatedData);

        return redirect(route('collector.dashboard'));
    }
}
