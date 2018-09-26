<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Admin;
use App\Rules\AdminEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * AdjustmentsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * display profile page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (request()->wantsJson()) {
            $user = Admin::findOrFail(Auth::user()->id)->first();

            return response($user, 200);
        }

        return view('admin.profile');
    }

    /**
     * update profile
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update()
    {
        $user = Auth::user();

        $request = $this->validateRequest();

        if (array_key_exists('password', $request))
            $request['password'] = bcrypt($request['password']);

        $user->update($request);

        return response($user, 201);
    }


    /**
     * validate request
     *
     * @return mixed
     */
    protected function validateRequest()
    {
        $user = Auth::user();

        $validator = Validator::make(request()->all(), [
                'email'      => ['required', 'email', "unique:users,email,{$user->id}", new AdminEmail],
                'first_name' => ['required'],
                'last_name'  => ['required'],
            ]
        );

        $validator->sometimes('old_password', 'hash:' . $user->password, function ($input) {
            return ! empty($input->old_password);
        });

        $validator->sometimes('old_password', 'required', function ($input) {
            return ! empty($input->password);
        });

        $validator->sometimes('password', 'required|confirmed', function ($input) {
            return ! empty($input->old_password);
        });

        return $validator->validate();
    }
}
