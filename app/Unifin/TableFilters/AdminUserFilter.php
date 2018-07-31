<?php

namespace Unifin\TableFilters;

class AdminUserFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['first_name', 'last_name' , 'username'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'created_at';

    /**
     * filter by group
     *
     * @return $this
     */
    public function filterGroup()
    {
        if (!is_null($this->request->filter1)) {
            if ($this->request->filter1 != "A") {
                $this->builder->where("access_level", "=", $this->request->filter1);
            }
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
        if (!is_null($this->request->filter2)) {
            if ($this->request->filter1 != "A") {
                $this->builder->where("active", "=", !! $this->request->filter2);
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

        $this->search()->sort()->filterGroup()->filterStatus();

        return $this->builder;
    }
}