<?php

namespace App\Unifin\Repositories\TodayTotals;

use App\Models\Tiger\UFN_TodayTotals2;
use App\Unifin\Repositories\TodayTotals\Contracts\TodayTotalsInterface;
use Carbon\Carbon;

class TodayTotals implements TodayTotalsInterface
{
    protected $postsToday;
    protected $groups;

    public function __construct()
    {
        $this->postsToday = UFN_TodayTotals2::all();
        $this->groups = $this->postsToday->pluck('group_name')->unique()->values();
    }

    public function gft()
    {
        return $this->groups->map(function ($item) {
            $data = $this->postsToday->where('group_name', $item)
                ->where('post_date', Carbon::now()->toDateString());
            $total = $data->sum('check_amount');
            $count = $data->count('check_amount');
            $avg = $data->average('check_amount');

            return compact('total', 'count', 'avg');
        });
    }

    public function thisMonth()
    {
        return $this->groups->map(function ($item) {
            $data = $this->postsToday->where('group_name', $item)
                ->where('post_date', '>=', Carbon::parse('first day of this month')->toDateString())
                ->where('post_date', '<=', Carbon::parse('last day of this month')->toDateString());
            $total = $data->sum('check_amount');
            $count = $data->count('check_amount');
            $avg = $data->average('check_amount');

            return compact('total', 'count', 'avg');
        });
    }

    public function thirtyDays()
    {
        return $this->groups->map(function ($item) {
            $data = $this->postsToday->where('group_name', $item)
                ->where('post_date', '>=', Carbon::parse('today')->toDateString())
                ->where('post_date', '<=', Carbon::parse('+30 days')->toDateString());
            $total = $data->sum('check_amount');
            $count = $data->count('check_amount');
            $avg = $data->average('check_amount');

            return compact('total', 'count', 'avg');
        });
    }

    public function all()
    {
        return $this->groups->map(function ($item) {
            $data = $this->postsToday->where('group_name', $item);
            $total = $data->sum('check_amount');
            $count = $data->count('check_amount');
            $avg = $data->average('check_amount');

            return compact('total', 'count', 'avg');
        });
    }
}