<?php

namespace App\Http\Controllers\Admin;

use App\Models\Lynx\Subsite;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Unifin\TableFilters\AdminSubSiteFilter;
use Unifin\Traits\Paginate;

class SubSitesController extends Controller
{
    use Paginate;

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:read site')->only('index');
        $this->middleware('permission:create site')->only('store');
//        $this->middleware('permission:update site')->only(['edit', 'update']);
    }

    /**
     * Display site page
     *
     * @param AdminSubSiteFilter $adminSubSiteFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(AdminSubSiteFilter $adminSubSiteFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getSubSites($adminSubSiteFilter);

            return response($response, 200);
        }

        return view('admin.subsites');
    }

    /**
     * persists a new sub site
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        // no additional validation for access_level since the
        // 'users' control are only accessed by super-admins
        $SubSite = $request->validate([
            'name'             => 'required',
            'site_id'          => 'required|numeric',
            'has_team_leaders' => 'boolean',
            'description'      => '',
        ]);

        $response = Subsite::create($SubSite);

        return response([], 200);
    }

    /**
     * get a sub site
     *
     * @param Subsite $subSite
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Subsite $subSite)
    {
        if (request()->wantsJson()) {
            return response($subSite, 200);
        }
    }

    /**
     * update the given sub site
     *
     * @param Subsite $subSite
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Subsite $subSite)
    {
        $newSubSite = request()->validate([
            'name'             => 'required',
            'site_id'          => 'required|numeric',
            'has_team_leaders' => 'boolean',
            'description'      => '',
        ]);

        $subSite->update($newSubSite);

        return response([], 201);
    }

    /**
     * get sub sites
     *
     * @param $adminSiteFilter
     * @return mixed
     */
    protected function getSubSites($adminSiteFilter)
    {
        $sites = Subsite::with('site')->tableFilters($adminSiteFilter);

        $results = $this->paginate($sites);

        return $results;
    }
}
