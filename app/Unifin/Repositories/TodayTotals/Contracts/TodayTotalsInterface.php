<?php

namespace App\Unifin\Repositories\TodayTotals\Contracts;

interface TodayTotalsInterface
{
    public function gft();

    public function thisMonth();

    public function thirtyDays();

    public function all();
}