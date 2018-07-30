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
        if (!is_null($this->request->status)) {
            if ($this->request->status != "A") {
                $this->builder->where("status", "=", $this->request->status);
            }
        }

        return $this;
    }

    /**
     * override apply tabulation
     *
     * @param $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        $this->search()->sort()->filterStatus()->filterCreatedAt();

        return $this->builder;
    }
}