<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Admin;

class CollectorOptionsController extends Controller
{
    /**
     * CollectorOptionsController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser', 'permission:read collectors']);
    }

    /**
     * get all roles
     *
     * @return \Illuminate\Support\Collection
     */
    public function index()
    {
        if (request()->wantsJson()) {

            $managers = Admin::select('id', 'first_name', 'last_name')->where('active', true)->role('manager')->get();

            $site_managers = Admin::select('id', 'first_name', 'last_name')->where('active', true)->role('site-manager')->get();

            $sub_site_managers = Admin::select('id', 'first_name', 'last_name')->where('active', true)->role('sub-site-manager')->get();

            $team_leaders = Admin::select('id', 'first_name', 'last_name')->where('active', true)->role('team-leader')->get();

            return Response(compact('managers', 'site_managers', 'sub_site_managers', $team_leaders), 200);
        }
    }
}
