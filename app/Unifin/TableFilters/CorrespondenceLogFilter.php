<?php

namespace Unifin\TableFilters;

class CorrespondenceLogFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['creator_name', 'client_name'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'updated_at';

    /**
     * Filter status.
     *
     * @return $this
     */
    public function filterStatus()
    {
        if (! is_null($this->request->filter1)) {
            if ($this->request->filter1 != "A") {
                $this->builder->where("status", "=", $this->request->filter1);
            }
        }

        return $this;
    }

    public function filterOrigin()
    {
        if (! is_null($this->request->filter2)) {
            if ($this->request->filter2 != "A") {
                $this->builder->where("correspondence_from", "=", $this->request->filter2);
            }
        }

        return $this;
    }

    public function filterType()
    {
        if (! is_null($this->request->filter3)) {
            if ($this->request->filter3 != "A") {
                $this->builder->where("correspondence_type", "=", $this->request->filter3);
            }
        }

        return $this;
    }

    public function filterDepartment()
    {
        if (! is_null($this->request->filter4)) {
            if ($this->request->filter4 != "A") {
                $this->builder->where("assigned_department", "=", $this->request->filter4);
            }
        }

        return $this;
    }

}