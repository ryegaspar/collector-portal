<?php

namespace App\Unifin\Repositories\TodayTotals;

interface TodayTotalsInterface
{
    public function groups();

    public function gft();

    public function thisMonth();

    public function thirtyDays();

    public function all();
}