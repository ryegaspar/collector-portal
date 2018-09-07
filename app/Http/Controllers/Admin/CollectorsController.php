<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lynx\Collector;
use App\Models\Lynx\Subsite;
use App\Rules\CollectOneId;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Unifin\TableFilters\AdminCollectorFilter;
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
     * //     *
     * @param AdminCollectorFilter $adminCollectorFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(AdminCollectorFilter $adminCollectorFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getCollectors($adminCollectorFilter);

            return response($response, 200);
        }

        return view('admin.collectors');
    }

    /**
     * persists a new collector
     *
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
                'sub_site_id' => ['required'],
                'first_name'  => ['required'],
                'last_name'   => ['required'],
                'start_date'  => ['required'],
                'manager_id'  => ['required', 'numeric']
            ]
        );

        $validator->sometimes('team_leader_id', ['required', 'numeric'], function ($input) {
            if (empty($input->sub_site_id))
                return false;
            $site = Subsite::find($input->sub_site_id);
            return !!$site->has_team_leaders;
        });

        return $validator->validate();
    }

    /**
     * Get collectors.
     *
     * @param $adminCollectorFilter
     * @return mixed
     */
    protected function getCollectors($adminCollectorFilter)
    {
        $admins = Collector::tableFilters($adminCollectorFilter)->with('sub_site:id,name');

        $results = $this->paginate($admins);

        return $results;
    }

}
