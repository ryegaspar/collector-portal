<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Site;
use Illuminate\Http\Request;
use Unifin\TableFilters\AdminSiteFilter;
use Unifin\Traits\Paginate;

class SitesController extends Controller
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
        $this->middleware('permission:update site')->only(['edit', 'update']);
    }

    /**
     * Display site page
     *
     * @param AdminSiteFilter $adminSiteFilter
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(AdminSiteFilter $adminSiteFilter)
    {
        if (request()->wantsJson()) {
            $response = $this->getSites($adminSiteFilter);

            return response($response, 200);
        }

        return view('admin.sites');
    }

    /**
     * persists a new site
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        // no additional validation for access_level since the
        // 'users' control are only accessed by super-admins
        $site = $request->validate([
            'name'        => 'required',
            'description' => '',
        ]);

        $response = Site::create($site);

        return response($response, 200);
    }

    /**
     * get sites
     *
     * @param Site $site
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Site $site)
    {
        if (request()->wantsJson()) {
            return response($site, 200);
        }
    }

    /**
     * Update the given site
     *
     * @param Site $site
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Site $site)
    {
        $newSite = request()->validate([
            'name'   => 'required',
            'description'    => '',
        ]);

        $site->update($newSite);

        return response([], 201);
    }

    /**
     * get sites
     *
     * @param $adminSiteFilter
     * @return mixed
     */
    protected function getSites($adminSiteFilter)
    {
        $sites = Site::tableFilters($adminSiteFilter);

        $results = $this->paginate($sites);

        return $results;
    }
}
