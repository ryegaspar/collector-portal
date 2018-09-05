<?php

namespace App\Http\Controllers\Admin;

use App\Rules\CollectOneId;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Unifin\Traits\Paginate;

class CollectorsController extends Controller
{
    use Paginate;

    /**
     * CollectorController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);

        $this->middleware('permission:read collector')->only('index');
        $this->middleware('permission:create collector')->only('store');
        $this->middleware('permission:update collector')->only(['edit', 'update']);
    }

    /**
     * display adjustments page
     *
     * //     * @param AdminUserFilter $adminUserFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
//    public function index(AdminUserFilter $adminUserFilter)
    {
//        if (request()->wantsJson()) {
//            $response = $this->getUsers($adminUserFilter);
//
//            return response($response, 200);
//        }

        return view('admin.collectors');
    }

    /**
     * persists a new collector
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store()
    {

        $request = $this->validateNewCollector();

//        $response = Admin::createUser($user);

        return response([], 200);
//        return response($response, 201);
    }


    /**
     * validate new collector
     *
     * @return mixed
     */
    protected function validateNewCollector()
    {

        $validator = Validator::make(request()->all(), [
                'category'       => 'required',
                'first_name'     => ['required'],
                'last_name'      => ['required'],
                'start_date'     => ['required'],
                'manager_id'     => ['required', 'numeric']
            ]
        );

        $validator->sometimes('tiger_user_id', ['required', 'min:3', new CollectOneId], function ($input) {
            return ! $input->category;
        });

        $validator->sometimes('desk', ['required'], function ($input) {
            return ! $input->category;
        });

        $validator->sometimes('team_leader_id', ['required', 'numeric'], function ($input) {
            return $input->category != 0;
        });

        return $validator->validate();
    }

}
