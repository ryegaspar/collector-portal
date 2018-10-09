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
     * SubSitesController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
        $this->middleware('permission:read site')->only('index');
        $this->middleware('permission:create site')->only('store');
        $this->middleware('permission:update site')->only(['edit', 'update']);
    }

    /**
     * Display listing of the resource.
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
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store(Request $request)
    {
        // no additional validation for access_level since the
        // 'users' control are only accessed by super-admins
        $request->merge(['prefixes' => implode(',', $request->prefixes)]);

        $subSite = $request->validate([
            'name'                            => 'required',
            'site_id'                         => 'required|numeric',
            'has_team_leaders'                => 'boolean',
            'description'                     => '',
            'min_desk_number'                 => 'required|numeric',
            'max_desk_number'                 => 'required|numeric',
            'collectone_id_assignment_method' => 'required|numeric',
            'prefixes'                        => ''
        ]);

        $response = Subsite::create($subSite);

        return response($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Subsite $subSite
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Subsite $subSite)
    {
        if ($subSite->prefixes) {
            $subSite->prefixes = explode(',', $subSite->prefixes);
        }

        if (request()->wantsJson()) {
            return response($subSite, 200);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Subsite $subSite
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Subsite $subSite)
    {
        request()->merge(['prefixes' => implode(',', request()->prefixes)]);

        $newSubSite = request()->validate([
            'name'                            => 'required',
            'site_id'                         => 'required|numeric',
            'has_team_leaders'                => 'boolean',
            'description'                     => '',
            'min_desk_number'                 => 'required|numeric',
            'max_desk_number'                 => 'required|numeric',
            'collectone_id_assignment_method' => 'required|numeric',
            'prefixes'                        => ''
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
        $sites = Subsite::with('site')
            ->withCount(['collectors' => function ($query) {
                $query->whereNull('date_terminated');
            }])
            ->tableFilters($adminSiteFilter);

        $results = $this->paginate($sites);

        return $results;
    }
}
