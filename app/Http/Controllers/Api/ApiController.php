<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lynx\Admin;
use App\Models\Lynx\LetterRequestType;
use App\Models\Lynx\Site;
use App\Models\Lynx\Subsite;
use App\Models\Tiger\UGP;

class ApiController extends Controller
{
    public function clients()
    {
        $client_lists = config('unifin.client_lists');

        return response(compact('client_lists'), 200);
    }

    public function subsiteOptions()
    {
        $collector_groups = UGP::all('UGP_CODE', 'UGP_DESC');
        $sites = Site::all('name', 'description');

        return response(compact('collector_groups', 'sites'), 200);
    }

    public function collectorOptions()
    {
        // may require to use permission:read collector
        $sub_sites = Subsite::select('id', 'name', 'has_team_leaders')->get();
        $team_leaders = Admin::select('id', 'first_name', 'last_name', 'sub_site_id')
            ->where('active', true)
            ->role('team-leader')
            ->get();
        $commission_structures = config('unifin.collector_commission_structures');
        $statuses = config('unifin.collector_statuses');
        $collector_groups = UGP::all('UGP_CODE', 'UGP_DESC');

        return response(compact('sub_sites', 'commission_structures', 'team_leaders', 'statuses', 'collector_groups'), 200);
    }

    public function letterRequestTypeOptions()
    {
        $letter_request_type = LetterRequestType::select('id', 'name')->where('active', true)->get();

        return response(compact('letter_request_type'), 200);
    }
}
