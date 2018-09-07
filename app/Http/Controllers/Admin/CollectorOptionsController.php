<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Admin;
use App\Models\Lynx\Subsite;

class CollectorOptionsController extends Controller
{
    /**
     * CollectorOptionsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser', 'permission:read collector']);
    }

    /**
     * get all roles
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        if (request()->wantsJson()) {

            $sub_sites = Subsite::select('id', 'name', 'has_team_leaders')->get();

            $team_leaders = Admin::select('id', 'first_name', 'last_name', 'sub_site_id')->where('active', true)->role('team-leader')->get();

            $commission_structures = config('unifin.collector_commission_structures');

            return response(compact('sub_sites', 'commission_structures', 'team_leaders'), 200);
        }
    }
}
