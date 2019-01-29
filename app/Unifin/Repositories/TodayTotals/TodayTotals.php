<?php

namespace App\Unifin\Repositories\TodayTotals;

use App\Models\Tiger\UFN_TodayTotals2;
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

    public function groups()
    {
        return $this->groups;
    }

    public function gft()
    {
        return $this->groups->map(function ($item) {
            return $this->generateData($item, Carbon::now()->toDateString(), Carbon::now()->toDateString());
        });
    }

    public function thisMonth()
    {
        return $this->groups->map(function ($item) {
            return $this->generateData($item,
                Carbon::parse('first day of this month')->toDateString(),
                Carbon::parse('last day of this month')->toDateString());
        });
    }

    public function thirtyDays()
    {
        return $this->groups->map(function ($item) {
            return $this->generateData($item,
                Carbon::parse('today')->toDateString(),
                Carbon::parse('+30 days')->toDateString());
        });
    }

    public function all()
    {
        return $this->groups->map(function ($item) {
            return $this->generateData($item);
        });
    }

    protected function generateData($item, $startDate = null, $endDate = null) {
        $data = $this->postsToday->where('group_name', $item);

        if ($startDate)
            $data = $data->where('post_date', '>=', $startDate);

        if ($endDate)
            $data = $data->where('post_date', '<=', $endDate);

        $total = number_format($data->sum('check_amount'), 2, '.', '');
        $count = number_format($data->count('check_amount'), 0, '', '');
        $avg = number_format($data->average('check_amount'), 2, '.', '');

        return compact('total', 'count', 'avg');
    }
}