<?php

namespace Unifin\UserTabulation;

use Carbon\Carbon;

class TransactionsTabulations extends Tabulation
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

    public function filterPaydate()
    {
        if ($this->request->paydate) {
            list($startDate, $endDate) = explode('|', $this->request->paydate);
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);

            $this->builder->whereBetween('PAY_DATE_O', [$startDate, $endDate]);
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

        $this->search()->sort()->filterPaydate();

        return $this->builder;
    }
}