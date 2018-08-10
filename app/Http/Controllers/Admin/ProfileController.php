<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Rules\UserEmail;
use App\User;
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
        return view('admin.profile');
    }

    /**
     * get the user profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show()
    {
        $user = User::findOrFail(Auth::user()->id)->first();
        if (request()->wantsJson()) {
            return response($user, 200);
        }
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
                'email'      => ['required', 'email', "unique:users,email,{$user->id}", new UserEmail],
                'first_name' => ['required'],
                'last_name'  => ['required'],
            ]
        );

        $validator->sometimes('old_password', 'hash:' . $user->password, function ($input) {
            return ! empty($input->old_password);
        });

        $validator->sometimes('password', 'required|confirmed', function ($input) {
            return ! empty($input->old_password);
        });

        return $validator->validate();
    }
}
