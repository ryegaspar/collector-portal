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
    protected $searches = ['id', 'title', 'first_name', 'last_name'];

    /**
     * default sort column
     *
     * @var string
     */
    protected $defaultSort = 'updated_at';

    /**
     * perform search on builder if any
     *
     * @return $this
     */
    protected function search()
    {
        $q = $this->builder->where($this->searches[0], 'like', "%{$this->request->search}%");
        $q = $q->orWhereHas('user', function($q) {
            $q->where('first_name', 'like', "%{$this->searches[1]}%");
        });
        $q = $q->orWhereHas('user', function($q) {
            $q->where('last_name', 'like', "%{$this->searches[2]}%");
        });

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