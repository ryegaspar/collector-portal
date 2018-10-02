<?php

namespace Unifin\TableFilters;

class AdminCollectorFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['first_name', 'last_name', 'username', 'desk'];

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
        if (! is_null($this->request->filter1)) {
            if ($this->request->filter1 != "A") {
                if (! ! $this->request->filter1) {
                    $this->builder->whereNull('date_terminated');
                } else {
                    $this->builder->whereNotNull('date_terminated');
                }
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
        if (! is_null($this->request->filter2)) {
            if ($this->request->filter2 != "A") {
                $this->builder->where("status_id", "=", $this->request->filter2);
            }
        }

        return $this;
    }

    /**
     * Filter Sub site.
     *
     * @return $this
     */
    public function filterSubSite()
    {
        if (! is_null($this->request->filter3)) {
            if ($this->request->filter3 != "A") {
                $this->builder->where('sub_site_id', $this->request->filter3);
            }
        }

        return $this;
    }

    /**
     * Filter team leader.
     *
     * @return $this
     */
    public function filterTeamLeader()
    {
        if (! is_null($this->request->filter4)) {
            if ($this->request->filter4 != "A") {
                $this->builder->where('team_leader_id', $this->request->filter4);
            }
        }

        return $this;
    }
}