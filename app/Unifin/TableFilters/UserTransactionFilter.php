<?php

namespace Unifin\TableFilters;

use Carbon\Carbon;

class UserTransactionFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['PAY_DBR_NO', 'PAY_NAME'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'PAY_DBR_NO';

    /**
     * filter by pay date
     *
     * @return $this
     */
    public function filterPaydate()
    {
        if ($this->request->date) {
            list($startDate, $endDate) = explode('|', $this->request->date);
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);

//            $this->builder->whereBetween('PAY_DATE_O', [$startDate, $endDate]);
            $this->builder
                ->whereDate('PAY_DATE_O','>=', $startDate)
                ->whereDate('PAY_DATE_O', '<=', $endDate);
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
        if ($this->request->status) {
            if ($this->request->status != "A") {
                $this->builder->where("PAY_STATUS", $this->request->status);
            }
        }

        return $this;
    }

    /**
     * apply tabulation
     *
     * @param $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;

        $this->search()->sort()->filterStatus()->filterPaydate();

        return $this->builder;
    }
}