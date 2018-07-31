<?php

namespace Unifin\TableFilters;

use Carbon\Carbon;

class AdminAdjustmentFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['collector_name', 'desk'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'date';

    /**
     * filter by date created_at
     *
     * @return $this
     */
    public function filterCreatedAt()
    {
        if ($this->request->date) {
            list($startDate, $endDate) = explode('|', $this->request->date);
            $startDate = Carbon::parse($startDate)->toDateString();
            $endDate = Carbon::parse($endDate)->toDateString();

//            $this->builder->whereBetween('created_at', [$startDate, $endDate]);
            $this->builder
                ->whereDate('created_at','>=', $startDate)
                ->whereDate('created_at', '<=', $endDate);
        }
        return $this;
    }

    /**
     * filter by status
     *
     * @return $this
     */
    public function filterStatus()
    {
        if (!is_null($this->request->filter1)) {
            if ($this->request->filter1 != "A") {
                $this->builder->where("status", "=", $this->request->filter1);
            }
        }

        return $this;
    }
}