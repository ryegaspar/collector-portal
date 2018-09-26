<?php

namespace Unifin\TableFilters;

class AdminCollectorFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['first_name', 'last_name' , 'username', 'desk'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'start_date';

    /**
     * Filter active/inactive.
     *
     * @return $this
     */
    public function filterActive()
    {
        if (!is_null($this->request->filter1)) {
            if ($this->request->filter1 != "A") {
                $this->builder->where("active", "=", !! $this->request->filter1);
            }
        }

        return $this;
    }

    /**
     * Filter status.
     *
     * @return $this
     */
    public function filterStatus()
    {
        if (!is_null($this->request->filter2)) {
            if ($this->request->filter2 != "A") {
                $this->builder->where("status_id", "=", $this->request->filter2);
            }
        }

        return $this;
    }

}