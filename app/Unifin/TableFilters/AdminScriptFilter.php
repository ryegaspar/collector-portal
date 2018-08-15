<?php

namespace Unifin\TableFilters;

use Carbon\Carbon;

class AdminScriptFilter extends TableFilter
{
    /**
     * columns to search
     *
     * @var array
     */
    protected $searches = ['id', 'title'];

    /**
     * columns on a related table to search for
     *
     * @var array
     */
    protected $searchesAs = ['user' => ['first_name', 'last_name']];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'updated_at';

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