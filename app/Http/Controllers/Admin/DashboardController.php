<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tiger\UFN_TodayTotals2;
use Illuminate\Support\Carbon;
use Unifin\Repositories\RawQueries;

class DashboardController extends Controller
{
    /**
     * DashboardController constructor.
     */
    public function __construct()
    {
        $this->middleware(['auth:admin', 'activeUser']);
    }

    /**
     * Display dashboard page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $postToday = UFN_TodayTotals2::all();

        $groupName = $postToday->pluck('group_name')->unique()->values();

        $gft = $groupName->map(function ($item) use ($postToday) {
            $data = $postToday->where('group_name', $item)
                ->where('post_date', Carbon::now()->toDateString());
//            $gft[$item] = [$data->sum('check_amount'), $data->count('check_amount'), $data->average('check_amount')];
//            return $gft;
//            return [$data->sum('check_amount'), $data->count('check_amount'), $data->average('check_amount')];
            $total = $data->sum('check_amount');
            $count = $data->count('check_amount');
            $avg = $data->average('check_amount');
            return compact('total', 'count', 'avg');
        });

        $thisMonth = $groupName->map(function ($item) use ($postToday) {
            $data = $postToday->where('group_name', $item)
                ->where('post_date', '>=', Carbon::parse('first day of this month')->toDateString())
                ->where('post_date', '<=', Carbon::parse('last day of this month')->toDateString());
            $total = $data->sum('check_amount');
            $count = $data->count('check_amount');
            $avg = $data->average('check_amount');
            return compact('total', 'count', 'avg');
        });

        $thirtyDays = $groupName->map(function ($item) use ($postToday) {
            $data = $postToday->where('group_name', $item)
                ->where('post_date', '>=', Carbon::parse('today')->toDateString())
                ->where('post_date', '<=', Carbon::parse('+30 days')->toDateString());
            $total = $data->sum('check_amount');
            $count = $data->count('check_amount');
            $avg = $data->average('check_amount');
            return compact('total', 'count', 'avg');
        });

        $all = $groupName->map(function ($item) use ($postToday) {
            $data = $postToday->where('group_name', $item);
            $total = $data->sum('check_amount');
            $count = $data->count('check_amount');
            $avg = $data->average('check_amount');
            return compact('total', 'count', 'avg');
        });

        dd($groupName, $gft, $thisMonth, $thirtyDays, $all);

        $hoursWorked = RawQueries::CollectorHoursWorked();

        return view('admin.dashboard', compact('hoursWorked'));
    }

    public function getData()
    {
//        if (request()->wantsJson()) {
            return UFN_TodayTotals2::all();
//        }
    }
}