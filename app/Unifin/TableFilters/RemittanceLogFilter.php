<?php

namespace Unifin\TableFilters;

class RemittanceLogFilter extends TableFilter
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
                $this->builder->where("report_sent", "=", $this->request->filter1);
            }
        }

        return $this;
    }

    public function filterCreatedByCollector()
    {
        if (! is_null($this->request->filter2)) {
            if ($this->request->filter2 != "A") {
                $this->builder->where("requestable_id", "=", request()->user()->id)
                    ->where('requestable_type', 'App\Models\Lynx\Collector');
            }
        }

        return $this;
    }
}